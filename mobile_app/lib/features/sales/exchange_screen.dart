import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../core/network/api_exception.dart';
import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../shared/pos_widgets.dart';

/// Exchange an active sale for another product (web: pages/sale/exchange.php).
class ExchangeScreen extends ConsumerStatefulWidget {
  const ExchangeScreen({super.key, required this.type, required this.salesId});

  final String type;
  final int salesId;

  @override
  ConsumerState<ExchangeScreen> createState() => _ExchangeScreenState();
}

class _ExchangeScreenState extends ConsumerState<ExchangeScreen> {
  Map<String, dynamic> _original = {};
  bool _loading = true;
  bool _submitting = false;

  final _searchCtrl = TextEditingController();
  List<Map<String, dynamic>> _results = [];
  Map<String, dynamic>? _picked;
  String? _pickedSize;
  double _price = 0;
  double _cash = 0;
  double _bank = 0;
  String _method = 'shop';
  String? _bankName;
  List<String> _banks = [];
  late final TextEditingController _cashCtrl;
  late final TextEditingController _bankCtrl;

  @override
  void initState() {
    super.initState();
    _cashCtrl = TextEditingController();
    _bankCtrl = TextEditingController();
    _load();
  }

  @override
  void dispose() {
    _searchCtrl.dispose();
    _cashCtrl.dispose();
    _bankCtrl.dispose();
    super.dispose();
  }

  Future<void> _load() async {
    try {
      final sale = await ref.read(salesRepositoryProvider).getSale(widget.type, widget.salesId);
      final banks = await ref.read(salesRepositoryProvider).banks();
      if (!mounted) return;
      setState(() {
        _original = sale;
        _banks = banks;
        _loading = false;
        _cash = (sale['cash'] as num?)?.toDouble() ?? 0;
        _bank = (sale['bank'] as num?)?.toDouble() ?? 0;
        _method = sale['method']?.toString() ?? 'shop';
        _bankName = sale['bank_name']?.toString();
        _cashCtrl.text = _cash.toStringAsFixed(2);
        _bankCtrl.text = _bank.toStringAsFixed(2);
      });
    } catch (e) {
      if (mounted) {
        setState(() => _loading = false);
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(_err(e))));
      }
    }
  }

  String _err(Object e) => e is ApiException ? e.message : e.toString();

  Future<void> _search() async {
    final q = _searchCtrl.text.trim();
    if (q.isEmpty) return;
    setState(() => _loading = true);
    try {
      final items = await ref.read(salesRepositoryProvider).searchProducts(widget.type, q: q);
      if (mounted) {
        setState(() {
          _results = items;
          _loading = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() => _loading = false);
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(_err(e))));
      }
    }
  }

  Future<void> _pickSize(String name, List<Map<String, dynamic>> sizes) async {
    final picked = await showModalBottomSheet<Map<String, dynamic>>(
      context: context,
      backgroundColor: AppColors.navyLight,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      builder: (ctx) => SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(20),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              Text('Size for $name', style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w800)),
              const SizedBox(height: 12),
              Wrap(
                spacing: 8,
                runSpacing: 8,
                children: sizes.map((s) {
                  final size = s['size']?.toString() ?? '';
                  final qty = (s['quantity'] as num?)?.toDouble() ?? 0;
                  return ActionChip(
                    label: Text('$size ($qty)'),
                    onPressed: qty > 0 ? () => Navigator.pop(ctx, s) : null,
                  );
                }).toList(),
              ),
            ],
          ),
        ),
      ),
    );
    if (picked == null) return;
    final size = picked['size']?.toString() ?? '';
    final price = await ref.read(salesRepositoryProvider).productPrice(widget.type, name, size);
    setState(() {
      _picked = {'name': name};
      _pickedSize = size;
      _price = price;
      _cash = price;
      _cashCtrl.text = _cash.toStringAsFixed(2);
    });
  }

  Future<void> _submit() async {
    if (_picked == null || _pickedSize == null) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Select replacement product')));
      return;
    }
    setState(() => _submitting = true);
    try {
      final result = await ref.read(salesRepositoryProvider).exchangeSale(
            type: widget.type,
            salesId: widget.salesId,
            name: _picked!['name'] as String,
            size: _pickedSize!,
            price: _price,
            cash: _cash,
            bank: _bank,
            method: _method,
            bankName: _bankName,
          );
      if (!mounted) return;
      HapticFeedback.heavyImpact();
      final newId = result['new_sales_id'];
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Exchange complete${newId != null ? ' · new sale #$newId' : ''}')),
      );
      context.pop(true);
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(_err(e)), backgroundColor: AppColors.danger),
        );
      }
    } finally {
      if (mounted) setState(() => _submitting = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final nameKey = '${widget.type}_name';
    final origName = _original[nameKey]?.toString() ?? '';

    return Scaffold(
      appBar: AppBar(title: const Text('Exchange Sale')),
      body: _loading && _original.isEmpty
          ? const Center(child: CircularProgressIndicator())
          : ListView(
              padding: const EdgeInsets.all(16),
              children: [
                Card(
                  child: Padding(
                    padding: const EdgeInsets.all(16),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text('Returning to stock', style: TextStyle(color: AppColors.textMuted, fontSize: 12)),
                        const SizedBox(height: 8),
                        Text(origName, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w800)),
                        Text('Size ${_original['size']} · ${currencyFmt.format((_original['price'] as num?) ?? 0)}',
                            style: const TextStyle(color: AppColors.textMuted)),
                      ],
                    ),
                  ),
                ),
                const SizedBox(height: 16),
                const Text('Replacement product', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 16)),
                const SizedBox(height: 8),
                Row(
                  children: [
                    Expanded(
                      child: TextField(
                        controller: _searchCtrl,
                        decoration: const InputDecoration(hintText: 'Search product name...', prefixIcon: Icon(Icons.search)),
                        onSubmitted: (_) => _search(),
                      ),
                    ),
                    const SizedBox(width: 8),
                    IconButton.filled(onPressed: _search, icon: const Icon(Icons.search)),
                  ],
                ),
                if (_picked != null) ...[
                  const SizedBox(height: 12),
                  ListTile(
                    tileColor: AppColors.accent.withValues(alpha: 0.15),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    title: Text(_picked!['name'] as String, style: const TextStyle(fontWeight: FontWeight.w700)),
                    subtitle: Text('Size $_pickedSize · ${currencyFmt.format(_price)}'),
                  ),
                ],
                ..._results.take(20).map((p) {
                  final name = p['name']?.toString() ?? '';
                  return ListTile(
                    title: Text(name),
                    subtitle: Text('${p['size']} · qty ${p['quantity']}'),
                    onTap: () async {
                      final data = await ref.read(salesRepositoryProvider).productSizes(widget.type, name);
                      final sizes = (data['sizes'] as List<dynamic>? ?? []).cast<Map<String, dynamic>>();
                      if (sizes.length == 1) {
                        await _pickSize(name, sizes);
                      } else if (sizes.isNotEmpty) {
                        await _pickSize(name, sizes);
                      }
                    },
                  );
                }),
                const SizedBox(height: 16),
                TextField(
                  keyboardType: TextInputType.number,
                  decoration: const InputDecoration(labelText: 'Cash'),
                  controller: _cashCtrl,
                  onChanged: (v) => setState(() => _cash = double.tryParse(v) ?? _cash),
                ),
                const SizedBox(height: 8),
                TextField(
                  keyboardType: TextInputType.number,
                  decoration: const InputDecoration(labelText: 'Bank'),
                  controller: _bankCtrl,
                  onChanged: (v) => setState(() => _bank = double.tryParse(v) ?? _bank),
                ),
                if (_banks.isNotEmpty) ...[
                  const SizedBox(height: 8),
                  DropdownButtonFormField<String>(
                    initialValue: _bankName != null && _banks.contains(_bankName) ? _bankName : null,
                    decoration: const InputDecoration(labelText: 'Bank name'),
                    items: [
                      const DropdownMenuItem<String>(value: null, child: Text('None')),
                      ..._banks.map((b) => DropdownMenuItem(value: b, child: Text(b))),
                    ],
                    onChanged: (v) => _bankName = v,
                  ),
                ],
                const SizedBox(height: 24),
                FilledButton(
                  onPressed: _submitting ? null : _submit,
                  style: FilledButton.styleFrom(backgroundColor: AppColors.warning, minimumSize: const Size.fromHeight(52)),
                  child: _submitting
                      ? const SizedBox(height: 22, width: 22, child: CircularProgressIndicator(strokeWidth: 2))
                      : const Text('Complete Exchange'),
                ),
              ],
            ),
    );
  }
}
