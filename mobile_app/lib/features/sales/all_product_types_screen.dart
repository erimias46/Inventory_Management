import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../shared/pos_widgets.dart';
import '../../core/utils/json_parse.dart';

/// Cross-category product summary (web: all_product_type.php).
class AllProductTypesScreen extends ConsumerStatefulWidget {
  const AllProductTypesScreen({super.key});

  @override
  ConsumerState<AllProductTypesScreen> createState() => _AllProductTypesScreenState();
}

class _AllProductTypesScreenState extends ConsumerState<AllProductTypesScreen> {
  List<Map<String, dynamic>> _items = [];
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    _items = await ref.read(opsRepositoryProvider).allProductTypes();
    setState(() => _loading = false);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('All Product Types'),
        actions: [IconButton(icon: const Icon(Icons.refresh), onPressed: _load)],
      ),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : _items.isEmpty
              ? const EmptyState(icon: Icons.category_outlined, message: 'No products in stock')
              : ListView.builder(
                  padding: const EdgeInsets.all(12),
                  itemCount: _items.length,
                  itemBuilder: (_, i) {
                    final row = _items[i];
                    return Card(
                      margin: const EdgeInsets.only(bottom: 8),
                      child: ExpansionTile(
                        title: Text(row['product_name']?.toString() ?? '', style: const TextStyle(fontWeight: FontWeight.w700)),
                        subtitle: Text('${row['category']} · ${row['sizes']}'),
                        children: [
                          Padding(
                            padding: const EdgeInsets.fromLTRB(16, 0, 16, 12),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                _row('Price', currencyFmt.format(parseJsonDouble(row['price']))),
                                _row('In stock now', '${row['total_quantity_now']}'),
                                _row('Total received', '${row['total_received']}'),
                                _row('Total sold', '${row['total_sold']}'),
                              ],
                            ),
                          ),
                        ],
                      ),
                    );
                  },
                ),
    );
  }

  Widget _row(String k, String v) => Padding(
        padding: const EdgeInsets.symmetric(vertical: 4),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(k, style: const TextStyle(color: AppColors.textMuted)),
            Text(v, style: const TextStyle(fontWeight: FontWeight.w600)),
          ],
        ),
      );
}
