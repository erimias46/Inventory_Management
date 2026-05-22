import 'package:fl_chart/fl_chart.dart';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';

import '../../core/models/app_category.dart';
import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../../core/utils/category_utils.dart';
import '../shared/pos_widgets.dart';
import '../../core/utils/json_parse.dart';

class DashboardScreen extends ConsumerStatefulWidget {
  const DashboardScreen({super.key, this.embedded = false});

  final bool embedded;

  @override
  ConsumerState<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends ConsumerState<DashboardScreen> {
  Map<String, dynamic> _data = {};
  List<double> _dailySeries = [];
  List<int> _dailyDays = [];
  bool _loading = true;
  String _period = '30';
  int _chartMonth = DateTime.now().month;
  int _chartYear = DateTime.now().year;
  int _tableYear = DateTime.now().year;

  static const _periods = [
    ('today', 'Today'),
    ('yesterday', 'Yesterday'),
    ('7', '7d'),
    ('30', '30d'),
    ('60', '60d'),
    ('180', '6mo'),
    ('365', '1y'),
  ];

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    final repo = ref.read(adminRepositoryProvider);
    try {
      _data = await repo.dashboardOverview(period: _period, year: _tableYear);
      final daily = await repo.dailySales(month: _chartMonth, year: _chartYear);
      _dailySeries = (daily['series'] as List<dynamic>? ?? []).map((e) => (e as num).toDouble()).toList();
      _dailyDays = (daily['categories'] as List<dynamic>? ?? []).map((e) => (e as num).toInt()).toList();
    } catch (_) {
      _data = {};
      _dailySeries = [];
      _dailyDays = [];
    }
    if (mounted) setState(() => _loading = false);
  }

  Map<String, dynamic> get _kpis => (_data['kpis'] as Map?)?.cast<String, dynamic>() ?? {};
  Map<String, dynamic> get _today => (_data['today'] as Map?)?.cast<String, dynamic>() ?? {};
  Map<String, dynamic> get _activity => (_data['activity'] as Map?)?.cast<String, dynamic>() ?? {};

  @override
  Widget build(BuildContext context) {
    final user = ref.watch(currentUserProvider);
    final periodLabel = _data['period_label']?.toString() ?? 'Last 30 days';

    final content = _loading
        ? const Center(child: CircularProgressIndicator())
        : RefreshIndicator(
            onRefresh: _load,
            child: ListView(
              padding: const EdgeInsets.fromLTRB(16, 8, 16, 24),
              children: [
                if (widget.embedded)
                  PosHeader(
                    title: 'Dashboard',
                    subtitle: user?.shopName.isNotEmpty == true ? user!.shopName : 'Store performance overview',
                  ),
                _PeriodChips(
                  periods: _periods,
                  selected: _period,
                  onSelected: (p) {
                    setState(() => _period = p);
                    _load();
                  },
                ),
                const SizedBox(height: 12),
                _SectionTitle('Today', icon: Icons.today_outlined),
                _TodayCard(today: _today),
                const SizedBox(height: 16),
                _SectionTitle(periodLabel, icon: Icons.insights_outlined),
                _KpiGrid(kpis: _kpis),
                const SizedBox(height: 16),
                _SectionTitle('Store activity', icon: Icons.storefront_outlined),
                _ActivityGrid(activity: _activity),
                const SizedBox(height: 16),
                _DailySalesChart(
                  series: _dailySeries,
                  days: _dailyDays,
                  month: _chartMonth,
                  year: _chartYear,
                  onMonthYearChanged: (m, y) {
                    setState(() {
                      _chartMonth = m;
                      _chartYear = y;
                    });
                    _load();
                  },
                ),
                const SizedBox(height: 16),
                _CategoryBreakdown(
                  items: (_data['by_category'] as List<dynamic>? ?? []).cast<Map<String, dynamic>>(),
                ),
                const SizedBox(height: 16),
                _BankSection(banks: (_data['banks'] as List<dynamic>? ?? []).cast<Map<String, dynamic>>()),
                const SizedBox(height: 16),
                _TopProducts(items: (_data['top_products'] as List<dynamic>? ?? []).cast<Map<String, dynamic>>()),
                const SizedBox(height: 16),
                _StockSection(stock: (_data['stock'] as Map?)?.cast<String, dynamic>() ?? {}),
                const SizedBox(height: 16),
                _MonthlyTable(
                  year: _tableYear,
                  months: (_data['monthly'] as List<dynamic>? ?? []).cast<Map<String, dynamic>>(),
                  onYearChanged: (y) {
                    setState(() => _tableYear = y);
                    _load();
                  },
                ),
              ],
            ),
          );

    if (widget.embedded) return content;
    return Scaffold(appBar: AppBar(title: const Text('Dashboard')), body: content);
  }
}

class _SectionTitle extends StatelessWidget {
  const _SectionTitle(this.text, {required this.icon});
  final String text;
  final IconData icon;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8),
      child: Row(
        children: [
          Icon(icon, size: 18, color: AppColors.accent),
          const SizedBox(width: 8),
          Text(text, style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w800)),
        ],
      ),
    );
  }
}

class _PeriodChips extends StatelessWidget {
  const _PeriodChips({required this.periods, required this.selected, required this.onSelected});
  final List<(String, String)> periods;
  final String selected;
  final ValueChanged<String> onSelected;

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      child: Row(
        children: periods.map((p) {
          final sel = p.$1 == selected;
          return Padding(
            padding: const EdgeInsets.only(right: 8),
            child: FilterChip(
              label: Text(p.$2),
              selected: sel,
              onSelected: (_) => onSelected(p.$1),
              selectedColor: AppColors.accent.withValues(alpha: 0.25),
              checkmarkColor: AppColors.accentBright,
            ),
          );
        }).toList(),
      ),
    );
  }
}

class _TodayCard extends StatelessWidget {
  const _TodayCard({required this.today});
  final Map<String, dynamic> today;

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Expanded(
                  child: _MiniStat(
                    'Sales amount',
                    currencyFmt.format(parseJsonDouble(today['earnings'])),
                    AppColors.accent,
                  ),
                ),
                Expanded(
                  child: _MiniStat(
                    'Transactions',
                    '${today['transactions'] ?? 0}',
                    AppColors.success,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(
                  child: _MiniStat(
                    'Units sold',
                    '${today['quantity'] ?? 0}',
                    AppColors.warning,
                  ),
                ),
                Expanded(
                  child: _MiniStat(
                    'Cash / Bank',
                    '${currencyFmt.format(parseJsonDouble(today['cash']))} · ${currencyFmt.format(parseJsonDouble(today['bank']))}',
                    AppColors.posGreen,
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}

class _MiniStat extends StatelessWidget {
  const _MiniStat(this.label, this.value, this.color);
  final String label;
  final String value;
  final Color color;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: TextStyle(fontSize: 11, color: color, fontWeight: FontWeight.w600)),
        const SizedBox(height: 4),
        Text(value, style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w800)),
      ],
    );
  }
}

class _KpiGrid extends StatelessWidget {
  const _KpiGrid({required this.kpis});
  final Map<String, dynamic> kpis;

  @override
  Widget build(BuildContext context) {
    final items = [
      ('Profit', currencyFmt.format(parseJsonDouble(kpis['profit'])), AppColors.success, Icons.trending_up),
      ('Earnings', currencyFmt.format(parseJsonDouble(kpis['earnings'])), AppColors.accent, Icons.attach_money),
      ('Units sold', '${kpis['quantity_sold'] ?? 0}', AppColors.warning, Icons.shopping_cart_outlined),
      ('Transactions', '${kpis['transactions'] ?? 0}', const Color(0xFF8B5CF6), Icons.receipt_long_outlined),
      ('Cash', currencyFmt.format(parseJsonDouble(kpis['cash'])), AppColors.posGreen, Icons.payments_outlined),
      ('Bank', currencyFmt.format(parseJsonDouble(kpis['bank'])), AppColors.accentBright, Icons.account_balance_outlined),
    ];
    return GridView.count(
      crossAxisCount: 2,
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      mainAxisSpacing: 10,
      crossAxisSpacing: 10,
      childAspectRatio: 1.35,
      children: items.map((i) => _KpiCard(i.$1, i.$2, i.$3, i.$4)).toList(),
    );
  }
}

class _KpiCard extends StatelessWidget {
  const _KpiCard(this.label, this.value, this.color, this.icon);
  final String label;
  final String value;
  final Color color;
  final IconData icon;

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Icon(icon, color: color, size: 22),
            const Spacer(),
            Text(label, style: TextStyle(color: color, fontSize: 11, fontWeight: FontWeight.w600)),
            const SizedBox(height: 4),
            Text(value, style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w800), maxLines: 2, overflow: TextOverflow.ellipsis),
          ],
        ),
      ),
    );
  }
}

class _ActivityGrid extends StatelessWidget {
  const _ActivityGrid({required this.activity});
  final Map<String, dynamic> activity;

  @override
  Widget build(BuildContext context) {
    final items = [
      ('In-store', activity['shop'] ?? 0, Icons.store, AppColors.accent),
      ('Delivery', activity['delivery'] ?? 0, Icons.local_shipping_outlined, AppColors.success),
      ('Exchange', activity['exchange'] ?? 0, Icons.swap_horiz, AppColors.warning),
      ('Refund', activity['refund'] ?? 0, Icons.undo, AppColors.danger),
    ];
    return GridView.count(
      crossAxisCount: 2,
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      mainAxisSpacing: 8,
      crossAxisSpacing: 8,
      childAspectRatio: 2.4,
      children: items
          .map(
            (i) => Card(
              child: Padding(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
                child: Row(
                  children: [
                    Icon(i.$3, color: i.$4, size: 28),
                    const SizedBox(width: 10),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Text('${i.$2}', style: const TextStyle(fontSize: 20, fontWeight: FontWeight.w800)),
                          Text(i.$1, style: const TextStyle(fontSize: 11, color: AppColors.textMuted)),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
            ),
          )
          .toList(),
    );
  }
}

class _DailySalesChart extends StatelessWidget {
  const _DailySalesChart({
    required this.series,
    required this.days,
    required this.month,
    required this.year,
    required this.onMonthYearChanged,
  });

  final List<double> series;
  final List<int> days;
  final int month;
  final int year;
  final void Function(int month, int year) onMonthYearChanged;

  @override
  Widget build(BuildContext context) {
    final maxY = series.isEmpty ? 10.0 : (series.reduce((a, b) => a > b ? a : b) * 1.2).clamp(5.0, double.infinity);
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                const Expanded(
                  child: Text('Daily quantity sold', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 16)),
                ),
                DropdownButton<int>(
                  value: month,
                  underline: const SizedBox(),
                  items: List.generate(12, (i) => DropdownMenuItem(value: i + 1, child: Text(DateFormat.MMMM().format(DateTime(2000, i + 1))))),
                  onChanged: (m) {
                    if (m != null) onMonthYearChanged(m, year);
                  },
                ),
                const SizedBox(width: 4),
                DropdownButton<int>(
                  value: year,
                  underline: const SizedBox(),
                  items: List.generate(DateTime.now().year - 2019, (i) {
                    final y = DateTime.now().year - i;
                    return DropdownMenuItem(value: y, child: Text('$y'));
                  }),
                  onChanged: (y) {
                    if (y != null) onMonthYearChanged(month, y);
                  },
                ),
              ],
            ),
            const SizedBox(height: 12),
            SizedBox(
              height: 220,
              child: series.isEmpty
                  ? const Center(child: Text('No sales data for this month', style: TextStyle(color: AppColors.textMuted)))
                  : BarChart(
                      BarChartData(
                        maxY: maxY,
                        gridData: FlGridData(
                          show: true,
                          drawVerticalLine: false,
                          getDrawingHorizontalLine: (v) => FlLine(color: Colors.white.withValues(alpha: 0.06)),
                        ),
                        titlesData: FlTitlesData(
                          leftTitles: const AxisTitles(sideTitles: SideTitles(showTitles: true, reservedSize: 32)),
                          bottomTitles: AxisTitles(
                            sideTitles: SideTitles(
                              showTitles: true,
                              reservedSize: 22,
                              getTitlesWidget: (v, _) {
                                final i = v.toInt();
                                if (i < 0 || i >= days.length) return const SizedBox.shrink();
                                if (days.length > 20 && i % 3 != 0) return const SizedBox.shrink();
                                return Text('${days[i]}', style: const TextStyle(fontSize: 9, color: AppColors.textMuted));
                              },
                            ),
                          ),
                          topTitles: const AxisTitles(sideTitles: SideTitles(showTitles: false)),
                          rightTitles: const AxisTitles(sideTitles: SideTitles(showTitles: false)),
                        ),
                        borderData: FlBorderData(show: false),
                        barGroups: series.asMap().entries.map((e) {
                          return BarChartGroupData(
                            x: e.key,
                            barRods: [
                              BarChartRodData(
                                toY: e.value,
                                color: AppColors.accentBright,
                                width: days.length > 20 ? 4 : 8,
                                borderRadius: const BorderRadius.vertical(top: Radius.circular(4)),
                              ),
                            ],
                          );
                        }).toList(),
                      ),
                    ),
            ),
          ],
        ),
      ),
    );
  }
}

class _CategoryBreakdown extends StatelessWidget {
  const _CategoryBreakdown({required this.items});
  final List<Map<String, dynamic>> items;

  @override
  Widget build(BuildContext context) {
    if (items.isEmpty) {
      return const Card(
        child: Padding(
          padding: EdgeInsets.all(16),
          child: Text('No category sales in this period', style: TextStyle(color: AppColors.textMuted)),
        ),
      );
    }
    final maxRev = items.map((e) => parseJsonDouble(e['revenue'])).fold(0.0, (a, b) => a > b ? a : b);
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text('Sales by category', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 16)),
            const SizedBox(height: 12),
            ...items.map((row) {
              final slug = row['slug']?.toString() ?? '';
              final rev = parseJsonDouble(row['revenue']);
              final qty = row['quantity'] ?? 0;
              final profit = parseJsonDouble(row['profit']);
              final pct = maxRev > 0 ? rev / maxRev : 0.0;
              AppCategory? cat;
              for (final c in fallbackCategories()) {
                if (c.slug == slug) {
                  cat = c;
                  break;
                }
              }
              return Padding(
                padding: const EdgeInsets.only(bottom: 12),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        if (cat != null) Icon(cat.materialIcon, size: 18, color: cat.accentColor),
                        const SizedBox(width: 8),
                        Expanded(child: Text(row['label']?.toString() ?? slug, style: const TextStyle(fontWeight: FontWeight.w700))),
                        Text(currencyFmt.format(rev), style: const TextStyle(color: AppColors.posGreen, fontWeight: FontWeight.w700)),
                      ],
                    ),
                    const SizedBox(height: 6),
                    ClipRRect(
                      borderRadius: BorderRadius.circular(4),
                      child: LinearProgressIndicator(value: pct, minHeight: 6, backgroundColor: AppColors.navyCard, color: cat?.accentColor ?? AppColors.accent),
                    ),
                    const SizedBox(height: 4),
                    Text('$qty units · profit ${currencyFmt.format(profit)}', style: const TextStyle(fontSize: 11, color: AppColors.textMuted)),
                  ],
                ),
              );
            }),
          ],
        ),
      ),
    );
  }
}

class _BankSection extends StatelessWidget {
  const _BankSection({required this.banks});
  final List<Map<String, dynamic>> banks;

  @override
  Widget build(BuildContext context) {
    final total = banks.fold<double>(0, (s, b) => s + parseJsonDouble(b['total']));
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text('Bank transactions', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 16)),
                if (total > 0)
                  Text(currencyFmt.format(total), style: const TextStyle(color: AppColors.accentBright, fontWeight: FontWeight.w800)),
              ],
            ),
            const SizedBox(height: 8),
            if (banks.isEmpty)
              const Text('No bank payments in period', style: TextStyle(color: AppColors.textMuted))
            else
              ...banks.map(
                (b) => ListTile(
                  dense: true,
                  contentPadding: EdgeInsets.zero,
                  leading: const Icon(Icons.account_balance, color: AppColors.accent),
                  title: Text(b['name']?.toString() ?? 'Bank'),
                  trailing: Text(currencyFmt.format(parseJsonDouble(b['total'])), style: const TextStyle(fontWeight: FontWeight.w700)),
                ),
              ),
          ],
        ),
      ),
    );
  }
}

class _TopProducts extends StatelessWidget {
  const _TopProducts({required this.items});
  final List<Map<String, dynamic>> items;

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text('Top 10 best sellers', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 16)),
            const SizedBox(height: 8),
            if (items.isEmpty)
              const Text('No sales in period', style: TextStyle(color: AppColors.textMuted))
            else
              ...items.asMap().entries.map((e) {
                final row = e.value;
                return ListTile(
                  dense: true,
                  contentPadding: EdgeInsets.zero,
                  leading: CircleAvatar(
                    radius: 14,
                    backgroundColor: AppColors.accent.withValues(alpha: 0.2),
                    child: Text('${e.key + 1}', style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w800)),
                  ),
                  title: Text(row['name']?.toString() ?? '', style: const TextStyle(fontWeight: FontWeight.w600)),
                  subtitle: Text('${row['quantity'] ?? 0} sold'),
                  trailing: Text(currencyFmt.format(parseJsonDouble(row['price'])), style: const TextStyle(color: AppColors.textMuted, fontSize: 12)),
                );
              }),
          ],
        ),
      ),
    );
  }
}

class _StockSection extends StatelessWidget {
  const _StockSection({required this.stock});
  final Map<String, dynamic> stock;

  @override
  Widget build(BuildContext context) {
    final recent = (stock['recent'] as List<dynamic>? ?? []).cast<Map<String, dynamic>>();
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                const Text('Stock summary', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 16)),
                const Spacer(),
                Chip(
                  label: Text('${stock['total_added'] ?? 0} added'),
                  backgroundColor: AppColors.warning.withValues(alpha: 0.15),
                  labelStyle: const TextStyle(fontWeight: FontWeight.w700, fontSize: 11),
                ),
              ],
            ),
            const SizedBox(height: 8),
            if (recent.isEmpty)
              const Text('No recent stock additions', style: TextStyle(color: AppColors.textMuted))
            else
              ...recent.map(
                (r) => ListTile(
                  dense: true,
                  contentPadding: EdgeInsets.zero,
                  leading: const Icon(Icons.inventory_2_outlined, color: AppColors.warning),
                  title: Text(r['name']?.toString() ?? '', style: const TextStyle(fontWeight: FontWeight.w600)),
                  subtitle: Text('${r['category'] ?? ''} · qty ${r['quantity'] ?? 0}'),
                ),
              ),
          ],
        ),
      ),
    );
  }
}

class _MonthlyTable extends StatelessWidget {
  const _MonthlyTable({required this.year, required this.months, required this.onYearChanged});
  final int year;
  final List<Map<String, dynamic>> months;
  final ValueChanged<int> onYearChanged;

  @override
  Widget build(BuildContext context) {
    final withSales = months.where((m) => parseJsonDouble(m['quantity']) > 0).toList();
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                const Expanded(child: Text('Monthly breakdown', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 16))),
                DropdownButton<int>(
                  value: year,
                  underline: const SizedBox(),
                  items: List.generate(DateTime.now().year - 2019, (i) {
                    final y = DateTime.now().year - i;
                    return DropdownMenuItem(value: y, child: Text('$y'));
                  }),
                  onChanged: (y) {
                    if (y != null) onYearChanged(y);
                  },
                ),
              ],
            ),
            const SizedBox(height: 8),
            if (withSales.isEmpty)
              const Text('No monthly data', style: TextStyle(color: AppColors.textMuted))
            else
              ...withSales.reversed.take(6).map((m) {
                return Padding(
                  padding: const EdgeInsets.symmetric(vertical: 8),
                  child: Row(
                    children: [
                      SizedBox(
                        width: 72,
                        child: Text(m['label']?.toString() ?? '', style: const TextStyle(fontWeight: FontWeight.w700)),
                      ),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text('${m['quantity']} items · ${currencyFmt.format(parseJsonDouble(m['revenue']))}', style: const TextStyle(fontSize: 12)),
                            Text(
                              'Avg sale ${currencyFmt.format(parseJsonDouble(m['avg_sale']))} · profit ${currencyFmt.format(parseJsonDouble(m['avg_profit']))}/unit',
                              style: const TextStyle(fontSize: 10, color: AppColors.textMuted),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                );
              }),
          ],
        ),
      ),
    );
  }
}
