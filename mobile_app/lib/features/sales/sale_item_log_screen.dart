import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';

import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../shared/pos_widgets.dart';
import '../../core/utils/json_parse.dart';

/// Per-product sale history (web: pages/sale/sale_log.php).
class SaleItemLogScreen extends ConsumerStatefulWidget {
  const SaleItemLogScreen({super.key});

  @override
  ConsumerState<SaleItemLogScreen> createState() => _SaleItemLogScreenState();
}

class _SaleItemLogScreenState extends ConsumerState<SaleItemLogScreen> {
  List<Map<String, dynamic>> _items = [];
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    _items = await ref.read(opsRepositoryProvider).saleItemLog();
    setState(() => _loading = false);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Sale Log'),
        actions: [IconButton(icon: const Icon(Icons.refresh), onPressed: _load)],
      ),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : _items.isEmpty
              ? const EmptyState(icon: Icons.receipt_long_outlined, message: 'No sale log entries')
              : ListView.builder(
                  padding: const EdgeInsets.all(12),
                  itemCount: _items.length,
                  itemBuilder: (_, i) {
                    final row = _items[i];
                    final isIn = row['log_direction'] == 'in';
                    return Card(
                      margin: const EdgeInsets.only(bottom: 8),
                      child: ListTile(
                        leading: Icon(
                          isIn ? Icons.add_circle_outline : Icons.remove_circle_outline,
                          color: isIn ? AppColors.success : AppColors.danger,
                        ),
                        title: Text(row['product_name']?.toString() ?? '', style: const TextStyle(fontWeight: FontWeight.w600)),
                        subtitle: Text(
                          '${row['source']} · ${row['size']} · ${row['status']}\n${_fmt(row['log_date']?.toString())}',
                        ),
                        isThreeLine: true,
                        trailing: Text(
                          currencyFmt.format(parseJsonDouble(row['price'])),
                          style: const TextStyle(fontWeight: FontWeight.w700, color: AppColors.posGreen),
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
      return DateFormat('d MMM y · HH:mm').format(DateTime.parse(raw));
    } catch (_) {
      return raw;
    }
  }
}
