import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/network/api_exception.dart';
import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';

/// Add inventory stock (web: pages/{module}/add_*.php).
class AddProductScreen extends ConsumerStatefulWidget {
  const AddProductScreen({super.key, required this.type});

  final String type;

  @override
  ConsumerState<AddProductScreen> createState() => _AddProductScreenState();
}

class _AddProductScreenState extends ConsumerState<AddProductScreen> {
  final _nameCtrl = TextEditingController();
  final _priceCtrl = TextEditingController();
  final _qtyCtrl = TextEditingController(text: '1');
  List<Map<String, dynamic>> _sizes = [];
  List<Map<String, dynamic>> _types = [];
  String? _selectedSize;
  int? _selectedSizeId;
  String? _selectedTypeLabel;
  int? _selectedTypeId;
  bool _loadingMeta = true;
  bool _saving = false;

  String get _title => '${widget.type[0].toUpperCase()}${widget.type.substring(1)}';

  @override
  void initState() {
    super.initState();
    _loadMeta();
  }

  @override
  void dispose() {
    _nameCtrl.dispose();
    _priceCtrl.dispose();
    _qtyCtrl.dispose();
    super.dispose();
  }

  Future<void> _loadMeta() async {
    try {
      final typeRows = await ref.read(adminRepositoryProvider).inventoryTypes(widget.type);
      if (!mounted) return;
      final sizes = <Map<String, dynamic>>[];
      final types = <Map<String, dynamic>>[];
      for (final row in typeRows) {
        if (row['size'] != null) {
          sizes.add({'size': row['size']?.toString(), 'id': row['id']});
        } else if (row['type'] != null) {
          types.add(row);
        }
      }
      setState(() {
        if (sizes.isNotEmpty) _sizes = sizes;
        if (types.isNotEmpty) _types = types;
        _loadingMeta = false;
      });
    } catch (_) {
      if (mounted) setState(() => _loadingMeta = false);
    }
    if (_sizes.isEmpty) {
      setState(() {
        _sizes = [
          {'size': 'S', 'id': 0},
          {'size': 'M', 'id': 0},
          {'size': 'L', 'id': 0},
          {'size': 'XL', 'id': 0},
        ];
      });
    }
  }

  String _err(Object e) => e is ApiException ? e.message : e.toString();

  Future<void> _save() async {
    final name = _nameCtrl.text.trim();
    final size = _selectedSize ?? '';
    final price = double.tryParse(_priceCtrl.text) ?? 0;
    final qty = double.tryParse(_qtyCtrl.text) ?? 0;
    if (name.isEmpty || size.isEmpty || price <= 0 || qty <= 0) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Fill name, size, price, and quantity')));
      return;
    }
    setState(() => _saving = true);
    try {
      final result = await ref.read(adminRepositoryProvider).createInventory(
            widget.type,
            name: name,
            size: size,
            price: price,
            quantity: qty,
            sizeId: _selectedSizeId,
            typeId: _selectedTypeId,
            typeLabel: _selectedTypeLabel,
          );
      if (!mounted) return;
      HapticFeedback.heavyImpact();
      final merged = result['merged'] == true;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(merged ? 'Stock quantity updated' : 'Product added')),
      );
      Navigator.pop(context, true);
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(_err(e)), backgroundColor: AppColors.danger),
        );
      }
    } finally {
      if (mounted) setState(() => _saving = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Add $_title')),
      body: _loadingMeta
          ? const Center(child: CircularProgressIndicator())
          : ListView(
              padding: const EdgeInsets.all(16),
              children: [
                TextField(
                  controller: _nameCtrl,
                  decoration: const InputDecoration(labelText: 'Product name', prefixIcon: Icon(Icons.label_outline)),
                  textCapitalization: TextCapitalization.words,
                ),
                const SizedBox(height: 12),
                if (_types.isNotEmpty)
                  DropdownButtonFormField<int>(
                    decoration: const InputDecoration(labelText: 'Product type'),
                    items: _types.map((t) {
                      final id = (t['id'] as num?)?.toInt() ?? 0;
                      final label = t['type']?.toString() ?? t['name']?.toString() ?? '$id';
                      return DropdownMenuItem(value: id, child: Text(label));
                    }).toList(),
                    onChanged: (id) {
                      final row = _types.firstWhere((t) => (t['id'] as num?)?.toInt() == id, orElse: () => {});
                      setState(() {
                        _selectedTypeId = id;
                        _selectedTypeLabel = row['type']?.toString();
                      });
                    },
                  ),
                const SizedBox(height: 12),
                DropdownButtonFormField<String>(
                  decoration: const InputDecoration(labelText: 'Size'),
                  items: _sizes
                      .map((s) => DropdownMenuItem(
                            value: s['size']?.toString(),
                            child: Text(s['size']?.toString() ?? ''),
                          ))
                      .toList(),
                  onChanged: (v) {
                    final row = _sizes.firstWhere((s) => s['size'] == v, orElse: () => {});
                    setState(() {
                      _selectedSize = v;
                      _selectedSizeId = (row['id'] as num?)?.toInt();
                    });
                  },
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: _priceCtrl,
                  keyboardType: const TextInputType.numberWithOptions(decimal: true),
                  decoration: const InputDecoration(labelText: 'Price', prefixIcon: Icon(Icons.attach_money)),
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: _qtyCtrl,
                  keyboardType: TextInputType.number,
                  decoration: const InputDecoration(labelText: 'Quantity', prefixIcon: Icon(Icons.inventory_2_outlined)),
                ),
                const SizedBox(height: 24),
                FilledButton(
                  onPressed: _saving ? null : _save,
                  style: FilledButton.styleFrom(minimumSize: const Size.fromHeight(52)),
                  child: _saving
                      ? const SizedBox(height: 22, width: 22, child: CircularProgressIndicator(strokeWidth: 2))
                      : const Text('Add to inventory'),
                ),
              ],
            ),
    );
  }
}
