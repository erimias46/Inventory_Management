import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../shared/pos_widgets.dart';

/// Edit product price/qty/buy price (web: editprice.php per category).
class EditPriceScreen extends ConsumerStatefulWidget {
  const EditPriceScreen({super.key, required this.type, required this.id});

  final String type;
  final int id;

  @override
  ConsumerState<EditPriceScreen> createState() => _EditPriceScreenState();
}

class _EditPriceScreenState extends ConsumerState<EditPriceScreen> {
  Map<String, dynamic> _item = {};
  final _priceCtrl = TextEditingController();
  final _buyCtrl = TextEditingController();
  final _qtyCtrl = TextEditingController();
  bool _loading = true;

  @override
  void dispose() {
    _priceCtrl.dispose();
    _buyCtrl.dispose();
    _qtyCtrl.dispose();
    super.dispose();
  }

  Future<void> _load() async {
    _item = await ref.read(adminRepositoryProvider).inventoryItem(widget.type, widget.id);
    _priceCtrl.text = (_item['price'] as num?)?.toString() ?? '';
    _buyCtrl.text = (_item['buy_price'] as num?)?.toString() ?? '';
    _qtyCtrl.text = (_item['quantity'] as num?)?.toString() ?? '';
    setState(() => _loading = false);
  }

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _save() async {
    await ref.read(adminRepositoryProvider).updateInventory(
          widget.type,
          widget.id,
          price: double.tryParse(_priceCtrl.text),
          buyPrice: double.tryParse(_buyCtrl.text),
          quantity: double.tryParse(_qtyCtrl.text),
        );
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Prices updated')));
      Navigator.pop(context, true);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Edit Price')),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  Text(_item['name']?.toString() ?? '', style: const TextStyle(fontSize: 20, fontWeight: FontWeight.w800)),
                  Text('Size ${_item['size']}', style: const TextStyle(color: AppColors.textMuted)),
                  const SizedBox(height: 20),
                  TextField(controller: _priceCtrl, keyboardType: TextInputType.number, decoration: const InputDecoration(labelText: 'Selling price')),
                  TextField(controller: _buyCtrl, keyboardType: TextInputType.number, decoration: const InputDecoration(labelText: 'Buy price')),
                  TextField(controller: _qtyCtrl, keyboardType: TextInputType.number, decoration: const InputDecoration(labelText: 'Quantity')),
                  const Spacer(),
                  FilledButton(onPressed: _save, child: const Text('Update')),
                ],
              ),
            ),
    );
  }
}
