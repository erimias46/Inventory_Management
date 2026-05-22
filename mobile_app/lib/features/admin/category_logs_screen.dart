import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';

import '../../core/models/app_category.dart';
import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../../core/utils/category_utils.dart';
import '../shared/pos_widgets.dart';

class CategoryLogsScreen extends ConsumerStatefulWidget {
  const CategoryLogsScreen({super.key, required this.logKind});

  /// refund | exchange | stock
  final String logKind;

  @override
  ConsumerState<CategoryLogsScreen> createState() => _CategoryLogsScreenState();
}

class _CategoryLogsScreenState extends ConsumerState<CategoryLogsScreen> {
  List<AppCategory> _categories = fallbackCategories();
  String _type = 'jeans';
  List<Map<String, dynamic>> _items = [];
  bool _loading = true;

  String get _title => switch (widget.logKind) {
        'refund' => 'Refund Logs',
        'exchange' => 'Exchange Logs',
        _ => 'Stock Logs',
      };

  @override
  void initState() {
    super.initState();
    _init();
  }

  Future<void> _init() async {
    final cats = await loadCategories(ref);
    if (!mounted) return;
    setState(() {
      _categories = cats;
      _type = cats.first.slug;
    });
    await _load();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    final repo = ref.read(opsRepositoryProvider);
    try {
      _items = switch (widget.logKind) {
        'refund' => await repo.refundLogs(_type),
        'exchange' => await repo.exchangeLogs(_type),
        _ => await repo.stockLogs(_type),
      };
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(_title),
        actions: [IconButton(icon: const Icon(Icons.refresh), onPressed: _load)],
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(12),
            child: DropdownButtonFormField<String>(
              initialValue: _type,
              decoration: const InputDecoration(labelText: 'Category'),
              items: _categories
                  .map((c) => DropdownMenuItem(value: c.slug, child: Text(c.label)))
                  .toList(),
              onChanged: (v) {
                if (v != null) {
                  setState(() => _type = v);
                  _load();
                }
              },
            ),
          ),
          Expanded(
            child: _loading
                ? const Center(child: CircularProgressIndicator())
                : _items.isEmpty
                    ? EmptyState(icon: Icons.inbox_outlined, message: 'No $_type records')
                    : ListView.builder(
                        padding: const EdgeInsets.all(12),
                        itemCount: _items.length,
                        itemBuilder: (_, i) {
                          final row = _items[i];
                          return Card(
                            margin: const EdgeInsets.only(bottom: 8),
                            child: ListTile(
                              title: Text(
                                row['product_name']?.toString() ??
                                    'Sale #${row['before_sale_id'] ?? row['sales_id'] ?? ''}',
                                style: const TextStyle(fontWeight: FontWeight.w600),
                              ),
                              subtitle: Text(_subtitle(row)),
                              trailing: Text(
                                row['price']?.toString() ?? '',
                                style: const TextStyle(color: AppColors.posGreen, fontWeight: FontWeight.w700),
                              ),
                            ),
                          );
                        },
                      ),
          ),
        ],
      ),
    );
  }

  String _subtitle(Map<String, dynamic> row) {
    if (widget.logKind == 'exchange') {
      return 'Before #${row['before_sale_id']} → After #${row['after_sale_id']}';
    }
    final date = row['update_date'] ?? row['sales_date'];
    final fmt = date?.toString() ?? '';
    try {
      return '${row['size']} · ${row['status'] ?? ''} · ${DateFormat('MMM d HH:mm').format(DateTime.parse(fmt))}';
    } catch (_) {
      return '${row['size']} · ${row['status'] ?? ''} · $fmt';
    }
  }
}
