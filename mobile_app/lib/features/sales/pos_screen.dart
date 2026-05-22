import 'dart:async';

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/config/api_config.dart';
import '../../core/network/api_exception.dart';
import '../../core/providers/app_providers.dart';
import '../../core/utils/category_utils.dart';
import '../../core/theme/app_theme.dart';
import '../shared/pos_widgets.dart';
import 'models/cart_line.dart';
import '../../core/utils/json_parse.dart';

class PosScreen extends ConsumerStatefulWidget {
  const PosScreen({super.key, this.fixedCategory});

  /// When set, locks POS to one category (web sale_jeans.php, sale_shoes.php, etc.).
  final String? fixedCategory;

  @override
  ConsumerState<PosScreen> createState() => _PosScreenState();
}

class _PosScreenState extends ConsumerState<PosScreen> {
  List<Map<String, dynamic>> _types = [];
  String? _selectedType;
  String? _fixedCategoryLabel;
  List<Map<String, dynamic>> _products = [];
  final _searchCtrl = TextEditingController();
  final List<CartLine> _cart = [];
  List<String> _banks = [];
  String? _bankName;
  String _method = 'shop';
  String? _deliveryReason;
  bool _bootstrapping = true;
  bool _loadingProducts = false;
  String? _loadError;
  bool _checkingOut = false;
  bool _cartExpanded = false;
  Timer? _searchDebounce;
  int _loadGeneration = 0;

  @override
  void initState() {
    super.initState();
    _bootstrap();
    _searchCtrl.addListener(_onSearchChanged);
  }

  @override
  void dispose() {
    _searchDebounce?.cancel();
    _searchCtrl.dispose();
    super.dispose();
  }

  Future<void> _bootstrap() async {
    setState(() {
      _bootstrapping = true;
      _loadError = null;
    });
    try {
      final repo = ref.read(salesRepositoryProvider);
      final cats = await loadCategories(ref);
      final types = categoriesToChipMaps(cats);
      final banks = await repo.banks();
      if (!mounted) return;
      final fixed = widget.fixedCategory;
      final filtered = fixed != null ? types.where((t) => t['key'] == fixed).toList() : types;
      setState(() {
        _types = filtered.isNotEmpty ? filtered : types;
        _banks = uniqueStrings(banks);
        _selectedType = fixed ?? (types.isNotEmpty ? types.first['key'] as String? : null);
        _fixedCategoryLabel = fixed != null ? categoryLabel(cats, fixed) : null;
        _bootstrapping = false;
      });
      if (types.isEmpty) {
        setState(() => _loadError = 'No categories for this shop. Check Settings or store setup.');
        return;
      }
      await _loadProducts();
    } catch (e) {
      if (!mounted) return;
      setState(() {
        _bootstrapping = false;
        _loadError = _formatError(e);
      });
    }
  }

  void _onSearchChanged() {
    _searchDebounce?.cancel();
    _searchDebounce = Timer(const Duration(milliseconds: 400), () {
      if (_selectedType != null) _loadProducts();
    });
  }

  String _formatError(Object e) {
    if (e is ApiException) return e.message;
    return e.toString().replaceAll('ApiException: ', '');
  }

  Future<void> _loadProducts() async {
    if (_selectedType == null) return;

    final gen = ++_loadGeneration;
    setState(() {
      _loadingProducts = true;
      _loadError = null;
    });

    try {
      final q = _searchCtrl.text.trim();
      final items = await ref.read(salesRepositoryProvider).searchProducts(
            _selectedType!,
            q: q.isEmpty ? null : q,
          );

      if (!mounted || gen != _loadGeneration) return;

      final apiBase = await ApiConfig.getBaseUrl();
      for (final p in items) {
        p['image'] = ApiConfig.normalizeProductImageUrl(
          p['image']?.toString(),
          apiBase,
          category: _selectedType,
        );
      }

      final grouped = <String, Map<String, dynamic>>{};
      for (final p in items) {
        final name = p['name']?.toString() ?? '';
        if (name.isEmpty) continue;
        if (!grouped.containsKey(name)) {
          grouped[name] = {
            'name': name,
            'min_price': p['price'],
            'total_qty': p['quantity'],
            'image': p['image'],
            'sizes': <Map<String, dynamic>>[p],
          };
        } else {
          final g = grouped[name]!;
          g['sizes'] = [...(g['sizes'] as List), p];
          g['total_qty'] = parseJsonDouble(g['total_qty']) + parseJsonDouble(p['quantity']);
          final price = parseJsonDouble(p['price']);
          if (price < (g['min_price'] as num)) g['min_price'] = price;
        }
      }

      setState(() {
        _products = grouped.values.toList();
        _loadingProducts = false;
        if (_products.isEmpty) {
          _loadError = 'No products in $_selectedType. Try another category.';
        }
      });
    } catch (e) {
      if (!mounted || gen != _loadGeneration) return;
      setState(() {
        _loadingProducts = false;
        _products = [];
        _loadError = _formatError(e);
      });
    }
  }

  double get _cartTotal => _cart.fold(0, (s, l) => s + l.lineTotal);

  Future<void> _openSizePicker(Map<String, dynamic> product) async {
    final name = product['name'] as String;
    List<Map<String, dynamic>> sizes = (product['sizes'] as List?)?.cast<Map<String, dynamic>>() ?? [];

    if (sizes.isEmpty && _selectedType != null) {
      try {
        final data = await ref.read(salesRepositoryProvider).productSizes(_selectedType!, name);
        sizes = (data['sizes'] as List<dynamic>? ?? []).cast<Map<String, dynamic>>();
      } catch (e) {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(_formatError(e))));
        }
        return;
      }
    }

    if (!mounted || sizes.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('No sizes in stock')));
      return;
    }

    final picked = await showModalBottomSheet<Map<String, dynamic>>(
      context: context,
      backgroundColor: AppColors.navyLight,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      builder: (ctx) => _SizePickerSheet(productName: name, sizes: sizes),
    );

    if (picked != null && _selectedType != null) {
      HapticFeedback.mediumImpact();
      final size = picked['size'] as String;
      try {
        final price = await ref.read(salesRepositoryProvider).productPrice(_selectedType!, name, size);
        setState(() {
          _cart.add(CartLine(
            type: _selectedType!,
            name: name,
            size: size,
            price: price,
            cash: price,
            imageUrl: product['image']?.toString(),
          ));
          _cartExpanded = true;
        });
      } catch (e) {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(_formatError(e))));
        }
      }
    }
  }

  Future<void> _checkout() async {
    if (_cart.isEmpty) return;
    final checkout = await showModalBottomSheet<Map<String, dynamic>>(
      context: context,
      isScrollControlled: true,
      backgroundColor: AppColors.navyLight,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      builder: (ctx) => _CheckoutSheet(
        cart: _cart,
        total: _cartTotal,
        banks: _banks,
        method: _method,
        bankName: _bankName,
        deliveryReason: _deliveryReason,
      ),
    );

    if (checkout == null || checkout['confirmed'] != true) return;

    _method = checkout['method'] as String? ?? _method;
    _bankName = checkout['bank_name'] as String?;
    _deliveryReason = checkout['reason'] as String?;

    setState(() => _checkingOut = true);
    try {
      final result = await ref.read(salesRepositoryProvider).submitMultiSale(
            lines: _cart.map((e) => e.toJson()).toList(),
            method: _method,
            bankName: _bankName,
            reason: _method == 'delivery' ? _deliveryReason : null,
          );
      if (!mounted) return;
      HapticFeedback.heavyImpact();
      final successCount = parseJsonInt(result['success_count'], _cart.length);
      final deliveryCount = parseJsonInt(result['delivery_count'], 0);
      final isDelivery = _method == 'delivery';
      await showDialog(
        context: context,
        builder: (ctx) => AlertDialog(
          backgroundColor: AppColors.navyCard,
          icon: const Icon(Icons.check_circle, color: AppColors.posGreen, size: 48),
          title: Text(isDelivery ? 'Delivery Created' : 'Sale Complete'),
          content: Text(
            isDelivery
                ? '$successCount item(s) sent for delivery${deliveryCount > 0 && _deliveryReason != null ? ' · ${_deliveryReason!}' : ''}.'
                : '$successCount item(s) sold successfully.',
          ),
          actions: [
            FilledButton(onPressed: () => Navigator.pop(ctx), child: const Text('Done')),
          ],
        ),
      );
      setState(() {
        _cart.clear();
        _cartExpanded = false;
      });
      await _loadProducts();
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(_formatError(e)), backgroundColor: AppColors.danger),
        );
      }
    } finally {
      if (mounted) setState(() => _checkingOut = false);
    }
  }

  Widget _buildProductArea() {
    if (_bootstrapping || _loadingProducts) {
      return const Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            CircularProgressIndicator(),
            SizedBox(height: 16),
            Text('Loading products from database...', style: TextStyle(color: AppColors.textMuted)),
          ],
        ),
      );
    }

    if (_loadError != null) {
      return Center(
        child: Padding(
          padding: const EdgeInsets.all(24),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Icon(Icons.error_outline, size: 48, color: AppColors.danger),
              const SizedBox(height: 16),
              Text(_loadError!, textAlign: TextAlign.center, style: const TextStyle(color: AppColors.textMuted)),
              const SizedBox(height: 16),
              FilledButton.icon(
                onPressed: _loadProducts,
                icon: const Icon(Icons.refresh),
                label: const Text('Retry'),
              ),
            ],
          ),
        ),
      );
    }

    if (_products.isEmpty) {
      return const EmptyState(icon: Icons.inventory_2_outlined, message: 'No products in this category');
    }

    return GridView.builder(
      padding: const EdgeInsets.all(12),
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 2,
        mainAxisSpacing: 10,
        crossAxisSpacing: 10,
        childAspectRatio: 0.82,
      ),
      itemCount: _products.length,
      itemBuilder: (_, i) {
        final p = _products[i];
        final qty = parseJsonDouble(p['total_qty']);
        return ProductGridTile(
          name: p['name'] as String,
          subtitle: '${qty.toStringAsFixed(0)} in stock',
          price: parseJsonDouble(p['min_price']),
          lowStock: qty < 3,
          imageUrl: p['image']?.toString(),
          onTap: () => _openSizePicker(p),
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Padding(
          padding: const EdgeInsets.fromLTRB(16, 0, 16, 0),
          child: TextField(
            controller: _searchCtrl,
            decoration: InputDecoration(
              hintText: 'Search products...',
              prefixIcon: const Icon(Icons.search, color: AppColors.textMuted),
              suffixIcon: IconButton(
                icon: const Icon(Icons.clear),
                onPressed: () {
                  _searchCtrl.clear();
                  _loadProducts();
                },
              ),
            ),
          ),
        ),
        const SizedBox(height: 10),
        if (_types.isNotEmpty && widget.fixedCategory == null)
          CategoryChipBar(
            categories: _types,
            selected: _selectedType,
            onSelected: (t) {
              setState(() => _selectedType = t);
              _loadProducts();
            },
          ),
        if (widget.fixedCategory != null && _selectedType != null)
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16),
            child: Align(
              alignment: Alignment.centerLeft,
              child: Chip(
                label: Text(
                  '${_fixedCategoryLabel ?? widget.fixedCategory} sale',
                  style: const TextStyle(fontWeight: FontWeight.w700),
                ),
              ),
            ),
          ),
        const SizedBox(height: 8),
        Expanded(
          flex: _cartExpanded ? 2 : 3,
          child: _buildProductArea(),
        ),
        if (_cart.isNotEmpty) ...[
          GestureDetector(
            onTap: () => setState(() => _cartExpanded = !_cartExpanded),
            child: Container(
              width: double.infinity,
              padding: const EdgeInsets.symmetric(vertical: 6),
              color: AppColors.navyCard,
              child: Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(_cartExpanded ? Icons.keyboard_arrow_down : Icons.keyboard_arrow_up, size: 20, color: AppColors.textMuted),
                  const SizedBox(width: 4),
                  Text(_cartExpanded ? 'Hide cart' : 'Show cart (${_cart.length})', style: const TextStyle(color: AppColors.textMuted, fontSize: 12)),
                ],
              ),
            ),
          ),
          if (_cartExpanded)
            SizedBox(
              height: 180,
              child: ListView.builder(
                itemCount: _cart.length,
                itemBuilder: (_, i) {
                  final line = _cart[i];
                  return Dismissible(
                    key: ValueKey('cart-$i-${line.name}-${line.size}'),
                    direction: DismissDirection.endToStart,
                    onDismissed: (_) => setState(() => _cart.removeAt(i)),
                    background: Container(color: AppColors.danger, alignment: Alignment.centerRight, padding: const EdgeInsets.only(right: 20), child: const Icon(Icons.delete_outline, color: Colors.white)),
                    child: ListTile(
                      dense: true,
                      title: Text(line.name, style: const TextStyle(fontWeight: FontWeight.w600)),
                      subtitle: Text('${line.type} · ${line.size} · qty ${line.quantity}'),
                      trailing: Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          IconButton(
                            icon: const Icon(Icons.remove_circle_outline, size: 20),
                            onPressed: line.quantity > 1
                                ? () => setState(() => _cart[i] = line.copyWith(quantity: line.quantity - 1))
                                : null,
                          ),
                          IconButton(
                            icon: const Icon(Icons.add_circle_outline, size: 20),
                            onPressed: () => setState(() => _cart[i] = line.copyWith(quantity: line.quantity + 1)),
                          ),
                          Text(
                            currencyFmt.format(line.lineTotal),
                            style: const TextStyle(fontWeight: FontWeight.w800, color: AppColors.posGreen),
                          ),
                        ],
                      ),
                    ),
                  );
                },
              ),
            ),
        ],
        CartSummaryBar(
          itemCount: _cart.length,
          total: _cartTotal,
          loading: _checkingOut,
          onCheckout: _checkout,
        ),
      ],
    );
  }
}

class _SizePickerSheet extends StatelessWidget {
  const _SizePickerSheet({required this.productName, required this.sizes});

  final String productName;
  final List<Map<String, dynamic>> sizes;

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Padding(
        padding: const EdgeInsets.all(20),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Text('Select size', style: Theme.of(context).textTheme.titleLarge?.copyWith(fontWeight: FontWeight.w800)),
            Text(productName, style: const TextStyle(color: AppColors.textMuted)),
            const SizedBox(height: 16),
            Wrap(
              spacing: 10,
              runSpacing: 10,
              children: sizes.map((s) {
                final size = s['size']?.toString() ?? '';
                final qty = parseJsonDouble(s['quantity']);
                final out = qty <= 0;
                return ActionChip(
                  label: Text('$size (${qty.toStringAsFixed(0)})'),
                  backgroundColor: out ? AppColors.navyCard : AppColors.accent.withValues(alpha: 0.2),
                  side: BorderSide(color: out ? AppColors.danger : AppColors.accent),
                  onPressed: out ? null : () => Navigator.pop(context, s),
                );
              }).toList(),
            ),
          ],
        ),
      ),
    );
  }
}

class _CheckoutSheet extends StatefulWidget {
  const _CheckoutSheet({
    required this.cart,
    required this.total,
    required this.banks,
    required this.method,
    required this.bankName,
    this.deliveryReason,
  });

  final List<CartLine> cart;
  final double total;
  final List<String> banks;
  final String method;
  final String? bankName;
  final String? deliveryReason;

  @override
  State<_CheckoutSheet> createState() => _CheckoutSheetState();
}

class _CheckoutSheetState extends State<_CheckoutSheet> {
  late String _method;
  String? _bank;
  late List<String> _uniqueBanks;
  late final TextEditingController _reasonCtrl;
  String? _reasonError;

  @override
  void initState() {
    super.initState();
    _method = widget.method == 'cash' ? 'shop' : widget.method;
    _uniqueBanks = uniqueStrings(widget.banks);
    final initial = widget.bankName?.trim();
    _bank = initial != null && initial.isNotEmpty && _uniqueBanks.contains(initial) ? initial : null;
    _reasonCtrl = TextEditingController(text: widget.deliveryReason ?? '');
  }

  @override
  void dispose() {
    _reasonCtrl.dispose();
    super.dispose();
  }

  void _confirm() {
    if (_method == 'delivery' && _reasonCtrl.text.trim().isEmpty) {
      setState(() => _reasonError = 'Enter who is delivering');
      return;
    }
    Navigator.pop(context, {
      'confirmed': true,
      'method': _method,
      'bank_name': _bank,
      'reason': _method == 'delivery' ? _reasonCtrl.text.trim() : null,
    });
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.only(bottom: MediaQuery.of(context).viewInsets.bottom),
      child: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(24),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              const Text('Checkout', style: TextStyle(fontSize: 22, fontWeight: FontWeight.w800)),
              Text('${widget.cart.length} items · ${currencyFmt.format(widget.total)}', style: const TextStyle(color: AppColors.textMuted)),
              const SizedBox(height: 20),
              SegmentedButton<String>(
                segments: const [
                  ButtonSegment(value: 'shop', label: Text('Shop'), icon: Icon(Icons.storefront)),
                  ButtonSegment(value: 'delivery', label: Text('Delivery'), icon: Icon(Icons.local_shipping)),
                ],
                selected: {_method},
                onSelectionChanged: (s) {
                  setState(() {
                    _method = s.first;
                    _reasonError = null;
                  });
                },
              ),
              if (_method == 'delivery') ...[
                const SizedBox(height: 16),
                TextField(
                  key: const Key('checkout_delivery_reason'),
                  controller: _reasonCtrl,
                  decoration: InputDecoration(
                    labelText: 'Delivery person',
                    hintText: 'Who is delivering? (same as web Reason)',
                    errorText: _reasonError,
                    prefixIcon: const Icon(Icons.person_outline),
                  ),
                  textCapitalization: TextCapitalization.words,
                  onChanged: (_) {
                    if (_reasonError != null) setState(() => _reasonError = null);
                  },
                ),
              ],
              if (_uniqueBanks.isNotEmpty) ...[
                const SizedBox(height: 16),
                DropdownButtonFormField<String>(
                  value: _bank,
                  decoration: const InputDecoration(labelText: 'Bank (optional)'),
                  items: [
                    const DropdownMenuItem<String>(value: null, child: Text('No bank')),
                    ..._uniqueBanks.map((b) => DropdownMenuItem<String>(value: b, child: Text(b))),
                  ],
                  onChanged: (v) => setState(() => _bank = v),
                ),
              ],
              const SizedBox(height: 24),
              FilledButton(
                onPressed: _confirm,
                style: FilledButton.styleFrom(backgroundColor: AppColors.posGreen, minimumSize: const Size.fromHeight(52)),
                child: Text(_method == 'delivery' ? 'Confirm delivery' : 'Confirm ${currencyFmt.format(widget.total)}'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
