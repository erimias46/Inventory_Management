import 'package:fl_chart/fl_chart.dart';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../shared/pos_widgets.dart';

class DashboardScreen extends ConsumerStatefulWidget {
  const DashboardScreen({super.key, this.embedded = false});

  final bool embedded;

  @override
  ConsumerState<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends ConsumerState<DashboardScreen> {
  Map<String, dynamic> _summary = {};
  List<double> _series = [];
  bool _loading = true;
  String _period = '30';

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    final repo = ref.read(adminRepositoryProvider);
    _summary = await repo.dashboardSummary(period: _period);
    final now = DateTime.now();
    final daily = await repo.dailySales(month: now.month, year: now.year);
    _series = (daily['series'] as List<dynamic>? ?? []).map((e) => (e as num).toDouble()).toList();
    setState(() => _loading = false);
  }

  @override
  Widget build(BuildContext context) {
    final content = _loading
        ? const Center(child: CircularProgressIndicator())
        : RefreshIndicator(
            onRefresh: _load,
            child: ListView(
              padding: const EdgeInsets.all(16),
              children: [
                if (widget.embedded)
                  const PosHeader(title: 'Dashboard', subtitle: 'Sales performance overview'),
                Row(
                  children: [
                    Expanded(child: _KpiCard('Revenue', currencyFmt.format((_summary['revenue'] as num?) ?? 0), AppColors.accent, Icons.trending_up)),
                    const SizedBox(width: 10),
                    Expanded(child: _KpiCard('Units', '${_summary['units_sold'] ?? 0}', AppColors.success, Icons.shopping_cart_outlined)),
                  ],
                ),
                const SizedBox(height: 10),
                Row(
                  children: [
                    Expanded(child: _KpiCard('Cash', currencyFmt.format((_summary['cash'] as num?) ?? 0), AppColors.posGreen, Icons.payments_outlined)),
                    const SizedBox(width: 10),
                    Expanded(child: _KpiCard('Bank', currencyFmt.format((_summary['bank'] as num?) ?? 0), const Color(0xFF8B5CF6), Icons.account_balance_outlined)),
                  ],
                ),
                const SizedBox(height: 20),
                Card(
                  child: Padding(
                    padding: const EdgeInsets.all(16),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            const Text('Daily Sales', style: TextStyle(fontWeight: FontWeight.w700, fontSize: 16)),
                            DropdownButton<String>(
                              value: _period,
                              underline: const SizedBox(),
                              items: const [
                                DropdownMenuItem(value: 'today', child: Text('Today')),
                                DropdownMenuItem(value: '7', child: Text('7d')),
                                DropdownMenuItem(value: '30', child: Text('30d')),
                                DropdownMenuItem(value: '365', child: Text('1y')),
                              ],
                              onChanged: (v) {
                                if (v != null) {
                                  setState(() => _period = v);
                                  _load();
                                }
                              },
                            ),
                          ],
                        ),
                        const SizedBox(height: 16),
                        SizedBox(
                          height: 200,
                          child: LineChart(
                            LineChartData(
                              gridData: FlGridData(
                                show: true,
                                drawVerticalLine: false,
                                getDrawingHorizontalLine: (v) => FlLine(color: Colors.white.withValues(alpha: 0.06), strokeWidth: 1),
                              ),
                              titlesData: const FlTitlesData(show: false),
                              borderData: FlBorderData(show: false),
                              lineBarsData: [
                                LineChartBarData(
                                  spots: _series.asMap().entries.map((e) => FlSpot(e.key.toDouble(), e.value)).toList(),
                                  isCurved: true,
                                  color: AppColors.accentBright,
                                  barWidth: 3,
                                  dotData: const FlDotData(show: false),
                                  belowBarData: BarAreaData(show: true, color: AppColors.accent.withValues(alpha: 0.12)),
                                ),
                              ],
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ],
            ),
          );

    if (widget.embedded) return content;
    return Scaffold(appBar: AppBar(title: const Text('Dashboard')), body: content);
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
        padding: const EdgeInsets.all(14),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Icon(icon, color: color, size: 22),
            const SizedBox(height: 10),
            Text(label, style: TextStyle(color: color, fontSize: 11, fontWeight: FontWeight.w600)),
            const SizedBox(height: 4),
            Text(value, style: const TextStyle(fontSize: 17, fontWeight: FontWeight.w800)),
          ],
        ),
      ),
    );
  }
}
