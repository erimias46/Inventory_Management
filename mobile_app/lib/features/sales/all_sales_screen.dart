import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:intl/intl.dart';

import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../shared/pos_widgets.dart';

class AllSalesScreen extends ConsumerStatefulWidget {
  const AllSalesScreen({super.key, this.embedded = false});

  final bool embedded;

  @override
  ConsumerState<AllSalesScreen> createState() => _AllSalesScreenState();
}

class _AllSalesScreenState extends ConsumerState<AllSalesScreen> {
  List<Map<String, dynamic>> _sales = [];
  List<Map<String, dynamic>> _filtered = [];
  bool _loading = true;
  final _searchCtrl = TextEditingController();
  String _filterType = 'all';

  @override
  void initState() {
    super.initState();
    _load();
    _searchCtrl.addListener(_applyFilter);
  }

  @override
  void dispose() {
    _searchCtrl.dispose();
    super.dispose();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    try {
      _sales = await ref.read(salesRepositoryProvider).listSales();
      _applyFilter();
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  void _applyFilter() {
    final q = _searchCtrl.text.toLowerCase();
    _filtered = _sales.where((s) {
      if (_filterType != 'all' && s['source'] != _filterType) return false;
      if (q.isEmpty) return true;
      return (s['product_name']?.toString() ?? '').toLowerCase().contains(q);
    }).toList();
    if (mounted) setState(() {});
  }

  String _formatDate(String? raw) {
    if (raw == null) return '';
    try {
      return DateFormat('MMM d · HH:mm').format(DateTime.parse(raw));
    } catch (_) {
      return raw;
    }
  }

  @override
  Widget build(BuildContext context) {
    final body = _loading
        ? const Center(child: CircularProgressIndicator())
        : Column(
            children: [
              Padding(
                padding: const EdgeInsets.fromLTRB(16, 8, 16, 0),
                child: TextField(
                  controller: _searchCtrl,
                  decoration: const InputDecoration(
                    hintText: 'Search sales...',
                    prefixIcon: Icon(Icons.search, color: AppColors.textMuted),
                  ),
                ),
              ),
              const SizedBox(height: 8),
              SizedBox(
                height: 36,
                child: ListView(
                  scrollDirection: Axis.horizontal,
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                  children: [
                    'all', 'jeans', 'shoes', 'top', 'wig', 'cosmetics', 'accessory', 'complete',
                  ].map((t) {
                    final sel = _filterType == t;
                    return Padding(
                      padding: const EdgeInsets.only(right: 8),
                      child: FilterChip(
                        label: Text(t == 'all' ? 'All' : t[0].toUpperCase() + t.substring(1)),
                        selected: sel,
                        onSelected: (_) {
                          setState(() => _filterType = t);
                          _applyFilter();
                        },
                      ),
                    );
                  }).toList(),
                ),
              ),
              Expanded(
                child: _filtered.isEmpty
                    ? const EmptyState(icon: Icons.receipt_long_outlined, message: 'No sales match your filters')
                    : RefreshIndicator(
                        onRefresh: _load,
                        child: ListView.separated(
                          padding: const EdgeInsets.all(12),
                          itemCount: _filtered.length,
                          separatorBuilder: (_, __) => const SizedBox(height: 8),
                          itemBuilder: (_, i) {
                            final s = _filtered[i];
                            final price = (s['price'] as num?)?.toDouble() ?? 0;
                            return Card(
                              child: InkWell(
                                borderRadius: BorderRadius.circular(14),
                                onTap: () => context.push('/sales/detail/${s['source']}/${s['sales_id']}'),
                                child: Padding(
                                  padding: const EdgeInsets.all(14),
                                  child: Row(
                                    children: [
                                      Container(
                                        width: 44,
                                        height: 44,
                                        decoration: BoxDecoration(
                                          color: AppColors.accent.withValues(alpha: 0.2),
                                          borderRadius: BorderRadius.circular(10),
                                        ),
                                        child: Center(
                                          child: Text(
                                            (s['source'] as String? ?? '?')[0].toUpperCase(),
                                            style: const TextStyle(fontWeight: FontWeight.w800, color: AppColors.accentBright),
                                          ),
                                        ),
                                      ),
                                      const SizedBox(width: 12),
                                      Expanded(
                                        child: Column(
                                          crossAxisAlignment: CrossAxisAlignment.start,
                                          children: [
                                            Text(
                                              s['product_name']?.toString() ?? '',
                                              style: const TextStyle(fontWeight: FontWeight.w700),
                                            ),
                                            Text(
                                              'Size ${s['size']} · ${_formatDate(s['sales_date']?.toString())}',
                                              style: const TextStyle(color: AppColors.textMuted, fontSize: 12),
                                            ),
                                          ],
                                        ),
                                      ),
                                      Column(
                                        crossAxisAlignment: CrossAxisAlignment.end,
                                        children: [
                                          Text(
                                            currencyFmt.format(price),
                                            style: const TextStyle(fontWeight: FontWeight.w800, color: AppColors.posGreen),
                                          ),
                                          Text(
                                            s['method']?.toString() ?? '',
                                            style: const TextStyle(color: AppColors.textMuted, fontSize: 11),
                                          ),
                                        ],
                                      ),
                                    ],
                                  ),
                                ),
                              ),
                            );
                          },
                        ),
                      ),
              ),
            ],
          );

    if (widget.embedded) {
      return Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          const PosHeader(title: 'Sales History', subtitle: 'Active sales across all categories'),
          Expanded(child: body),
        ],
      );
    }

    return Scaffold(
      appBar: AppBar(
        title: const Text('All Sales'),
        actions: [IconButton(icon: const Icon(Icons.refresh), onPressed: _load)],
      ),
      body: body,
    );
  }
}
