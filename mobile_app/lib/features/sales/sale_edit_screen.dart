import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../core/network/api_exception.dart';
import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../../core/utils/json_parse.dart';

/// Edit sale payment fields (web: pages/sale/edit.php).
class SaleEditScreen extends ConsumerStatefulWidget {
  const SaleEditScreen({super.key, required this.type, required this.id});

  final String type;
  final int id;

  @override
  ConsumerState<SaleEditScreen> createState() => _SaleEditScreenState();
}

class _SaleEditScreenState extends ConsumerState<SaleEditScreen> {
  final _priceCtrl = TextEditingController();
  final _cashCtrl = TextEditingController();
  final _bankCtrl = TextEditingController();
  String _method = 'shop';
  bool _loading = true;
  bool _saving = false;

  @override
  void dispose() {
    _priceCtrl.dispose();
    _cashCtrl.dispose();
    _bankCtrl.dispose();
    super.dispose();
  }

  Future<void> _load() async {
    final sale = await ref.read(salesRepositoryProvider).getSale(widget.type, widget.id);
    _priceCtrl.text = parseJsonDouble(sale['price']).toString();
    _cashCtrl.text = parseJsonDouble(sale['cash']).toString();
    _bankCtrl.text = parseJsonDouble(sale['bank']).toString();
    _method = sale['method']?.toString() ?? 'shop';
    setState(() => _loading = false);
  }

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _save() async {
    setState(() => _saving = true);
    try {
      await ref.read(salesRepositoryProvider).updateSale(
            widget.type,
            widget.id,
            price: double.tryParse(_priceCtrl.text),
            cash: double.tryParse(_cashCtrl.text),
            bank: double.tryParse(_bankCtrl.text),
            method: _method,
          );
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Sale updated')));
        context.pop(true);
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(e is ApiException ? e.message : '$e'), backgroundColor: AppColors.danger),
        );
      }
    } finally {
      if (mounted) setState(() => _saving = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Edit Sale')),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  TextField(controller: _priceCtrl, keyboardType: TextInputType.number, decoration: const InputDecoration(labelText: 'Price')),
                  TextField(controller: _cashCtrl, keyboardType: TextInputType.number, decoration: const InputDecoration(labelText: 'Cash')),
                  TextField(controller: _bankCtrl, keyboardType: TextInputType.number, decoration: const InputDecoration(labelText: 'Bank')),
                  const SizedBox(height: 12),
                  DropdownButtonFormField<String>(
                    initialValue: _method,
                    decoration: const InputDecoration(labelText: 'Method'),
                    items: const [
                      DropdownMenuItem(value: 'shop', child: Text('Shop')),
                      DropdownMenuItem(value: 'delivery', child: Text('Delivery')),
                    ],
                    onChanged: (v) => setState(() => _method = v ?? 'shop'),
                  ),
                  const Spacer(),
                  FilledButton(
                    onPressed: _saving ? null : _save,
                    style: FilledButton.styleFrom(minimumSize: const Size.fromHeight(52)),
                    child: Text(_saving ? 'Saving...' : 'Save changes'),
                  ),
                ],
              ),
            ),
    );
  }
}
