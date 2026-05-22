import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../shared/pos_widgets.dart';
import '../../core/utils/json_parse.dart';

class SaleDetailScreen extends ConsumerStatefulWidget {
  const SaleDetailScreen({super.key, required this.type, required this.id});

  final String type;
  final int id;

  @override
  ConsumerState<SaleDetailScreen> createState() => _SaleDetailScreenState();
}

class _SaleDetailScreenState extends ConsumerState<SaleDetailScreen> {
  Map<String, dynamic> _sale = {};
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    final data = await ref.read(salesRepositoryProvider).getSale(widget.type, widget.id);
    setState(() {
      _sale = data;
      _loading = false;
    });
  }

  Future<void> _delete() async {
    final ok = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        backgroundColor: AppColors.navyCard,
        title: const Text('Delete sale?'),
        content: const Text('This permanently removes the sale record.'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx, false), child: const Text('Cancel')),
          FilledButton(
            style: FilledButton.styleFrom(backgroundColor: AppColors.danger),
            onPressed: () => Navigator.pop(ctx, true),
            child: const Text('Delete'),
          ),
        ],
      ),
    );
    if (ok == true) {
      await ref.read(salesRepositoryProvider).deleteSale(widget.type, widget.id);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Sale deleted')));
        context.pop();
      }
    }
  }

  Future<void> _refund() async {
    final ok = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        backgroundColor: AppColors.navyCard,
        title: const Text('Refund sale?'),
        content: const Text('This will restore stock and mark the sale as refunded.'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx, false), child: const Text('Cancel')),
          FilledButton(
            style: FilledButton.styleFrom(backgroundColor: AppColors.danger),
            onPressed: () => Navigator.pop(ctx, true),
            child: const Text('Refund'),
          ),
        ],
      ),
    );
    if (ok == true) {
      await ref.read(salesRepositoryProvider).refundSale(widget.type, widget.id);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Sale refunded')));
        context.pop();
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final user = ref.watch(currentUserProvider);
    final nameKey = '${widget.type}_name';
    final canRefund = user?.isMasterAdmin == true || user?.hasModule('refundsale${widget.type}') == true;
    final canExchange = (_sale['status']?.toString() == 'active') &&
        (user?.isMasterAdmin == true || user?.hasModule('exchangesale${widget.type}') == true);
    final canDelete = user?.isMasterAdmin == true || user?.hasModule('deletesale${widget.type}') == true;
    final canEdit = user?.isMasterAdmin == true || user?.hasModule('editsale${widget.type}') == true;

    return Scaffold(
      appBar: AppBar(title: const Text('Sale Receipt')),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : ListView(
              padding: const EdgeInsets.all(16),
              children: [
                Card(
                  child: Padding(
                    padding: const EdgeInsets.all(20),
                    child: Column(
                      children: [
                        const Icon(Icons.receipt_long, size: 48, color: AppColors.accent),
                        const SizedBox(height: 12),
                        Text(
                          _sale[nameKey]?.toString() ?? 'Sale',
                          style: const TextStyle(fontSize: 20, fontWeight: FontWeight.w800),
                          textAlign: TextAlign.center,
                        ),
                        const SizedBox(height: 4),
                        Text(
                          currencyFmt.format(parseJsonDouble(_sale['price'])),
                          style: const TextStyle(fontSize: 32, fontWeight: FontWeight.w800, color: AppColors.posGreen),
                        ),
                        const SizedBox(height: 16),
                        const Divider(),
                        _DetailRow('Category', widget.type),
                        _DetailRow('Size', _sale['size']?.toString() ?? ''),
                        _DetailRow('Cash', currencyFmt.format(parseJsonDouble(_sale['cash']))),
                        _DetailRow('Bank', currencyFmt.format(parseJsonDouble(_sale['bank']))),
                        _DetailRow('Method', _sale['method']?.toString() ?? ''),
                        _DetailRow('Date', _sale['sales_date']?.toString() ?? ''),
                        _DetailRow('Status', _sale['status']?.toString() ?? ''),
                        _DetailRow('Sale ID', '#${widget.id}'),
                      ],
                    ),
                  ),
                ),
                if (canEdit) ...[
                  const SizedBox(height: 16),
                  OutlinedButton.icon(
                    onPressed: () async {
                      final done = await context.push<bool>('/sales/edit/${widget.type}/${widget.id}');
                      if (done == true && mounted) _load();
                    },
                    icon: const Icon(Icons.edit_outlined),
                    label: const Text('Edit Sale'),
                    style: OutlinedButton.styleFrom(minimumSize: const Size.fromHeight(52)),
                  ),
                ],
                if (canRefund) ...[
                  const SizedBox(height: 12),
                  OutlinedButton.icon(
                    onPressed: _refund,
                    icon: const Icon(Icons.undo, color: AppColors.danger),
                    label: const Text('Refund Sale', style: TextStyle(color: AppColors.danger)),
                    style: OutlinedButton.styleFrom(minimumSize: const Size.fromHeight(52), side: const BorderSide(color: AppColors.danger)),
                  ),
                ],
                if (canExchange) ...[
                  const SizedBox(height: 16),
                  FilledButton.icon(
                    onPressed: () async {
                      final done = await context.push<bool>('/sales/exchange/${widget.type}/${widget.id}');
                      if (done == true && mounted) context.pop();
                    },
                    icon: const Icon(Icons.swap_horiz),
                    label: const Text('Exchange Product'),
                    style: FilledButton.styleFrom(
                      backgroundColor: AppColors.warning,
                      minimumSize: const Size.fromHeight(52),
                    ),
                  ),
                ],
                if (canDelete) ...[
                  const SizedBox(height: 12),
                  OutlinedButton.icon(
                    onPressed: _delete,
                    icon: const Icon(Icons.delete_outline, color: AppColors.danger),
                    label: const Text('Delete Sale', style: TextStyle(color: AppColors.danger)),
                    style: OutlinedButton.styleFrom(minimumSize: const Size.fromHeight(52), side: const BorderSide(color: AppColors.danger)),
                  ),
                ],
              ],
            ),
    );
  }
}

class _DetailRow extends StatelessWidget {
  const _DetailRow(this.label, this.value);

  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: const TextStyle(color: AppColors.textMuted)),
          Text(value, style: const TextStyle(fontWeight: FontWeight.w600)),
        ],
      ),
    );
  }
}
