import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';

import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../shared/pos_widgets.dart';
import '../../core/utils/json_parse.dart';

/// Products added to inventory (web: pages/sale/products_log.php).
class ProductsInLogScreen extends ConsumerStatefulWidget {
  const ProductsInLogScreen({super.key});

  @override
  ConsumerState<ProductsInLogScreen> createState() => _ProductsInLogScreenState();
}

class _ProductsInLogScreenState extends ConsumerState<ProductsInLogScreen> {
  List<Map<String, dynamic>> _items = [];
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    _items = await ref.read(opsRepositoryProvider).productsInLog();
    setState(() => _loading = false);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Products In'),
        actions: [IconButton(icon: const Icon(Icons.refresh), onPressed: _load)],
      ),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : _items.isEmpty
              ? const EmptyState(icon: Icons.inventory_2_outlined, message: 'No incoming product records')
              : ListView.builder(
                  padding: const EdgeInsets.all(12),
                  itemCount: _items.length,
                  itemBuilder: (_, i) {
                    final row = _items[i];
                    return Card(
                      margin: const EdgeInsets.only(bottom: 8),
                      child: ListTile(
                        title: Text(row['product_name']?.toString() ?? '', style: const TextStyle(fontWeight: FontWeight.w700)),
                        subtitle: Text(
                          '${row['product_type']} · ${row['sizes']}\n${_fmt(row['created_at']?.toString())} · qty ${row['total_quantity']}',
                        ),
                        isThreeLine: true,
                        trailing: Text(
                          currencyFmt.format(parseJsonDouble(row['price'])),
                          style: const TextStyle(color: AppColors.posGreen, fontWeight: FontWeight.w700),
                        ),
                      ),
                    );
                  },
                ),
    );
  }

  String _fmt(String? raw) {
    if (raw == null) return '';
    try {
      return DateFormat('d MMM y HH:mm').format(DateTime.parse(raw));
    } catch (_) {
      return raw;
    }
  }
}
