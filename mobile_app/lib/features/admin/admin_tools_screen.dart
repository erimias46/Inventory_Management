import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../core/models/user_model.dart';
import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../shared/pos_widgets.dart';

/// Hub for web admin features (backup, constants, export, etc.).
class AdminToolsScreen extends ConsumerWidget {
  const AdminToolsScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final user = ref.watch(currentUserProvider);
    if (user == null) return const Scaffold(body: Center(child: Text('Not signed in')));
    return Scaffold(
      appBar: AppBar(title: const Text('Operations')),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          const PosHeader(title: 'Web parity tools', subtitle: 'Logs, backup, constants & more'),
          _tile(context, Icons.undo, 'Refund logs', 'Per-category refund history', AppColors.danger,
              '/admin/logs/refund', user.isMasterAdmin || _anyRefund(user)),
          _tile(context, Icons.swap_horiz, 'Exchange logs', 'Before/after sale IDs', AppColors.warning,
              '/admin/logs/exchange', user.isMasterAdmin || _anyExchange(user)),
          _tile(context, Icons.history, 'Stock logs', 'Sales log / stock movement', AppColors.accent,
              '/admin/logs/stock', user.isMasterAdmin),
          _tile(context, Icons.receipt_long, 'Sale item log', 'Unified per-product log', AppColors.accentBright,
              '/sales/sale-log', user.hasModule('logsale') || user.isMasterAdmin,
              onTap: () => context.push('/sales/sale-log')),
          _tile(context, Icons.inventory_2_outlined, 'Products in', 'Added inventory log', AppColors.success,
              '/sales/products-in', user.isMasterAdmin,
              onTap: () => context.push('/sales/products-in')),
          _tile(context, Icons.category_outlined, 'All product types', 'Cross-category stock view', AppColors.warning,
              '/sales/all-product-types', user.isMasterAdmin,
              onTap: () => context.push('/sales/all-product-types')),
          _tile(context, Icons.table_chart, 'Price constants', 'd_constants tables', AppColors.success,
              '/admin/constants', user.hasModule('constant')),
          _tile(context, Icons.backup, 'Backup database', 'Create & list SQL backups', AppColors.accentBright,
              '/admin/backup', user.hasModule('backup')),
          _tile(context, Icons.file_download, 'Export data', 'Export any DB table', AppColors.posGreen,
              '/admin/export', user.isMasterAdmin),
          _tile(context, Icons.email_outlined, 'Email subscribers', 'Notification emails', AppColors.warning,
              '/admin/emails', user.hasModule('email')),
          _tile(context, Icons.store, 'Store & modules', 'Company info & category toggles', AppColors.accent,
              '/admin/store-settings', user.isMasterAdmin),
          _tile(context, Icons.print, 'Digital pages (Top)', 'single_page jobs', AppColors.textMuted,
              '/admin/digital', user.isMasterAdmin),
          _tile(context, Icons.checkroom, 'Jeans / all categories', 'Full inventory (incl. jeans)', AppColors.accent,
              '/admin', user.isMasterAdmin, onTap: () => context.go('/admin')),
        ],
      ),
    );
  }

  bool _anyRefund(AppUser u) {
    for (final t in ['jeans', 'shoes', 'top', 'complete', 'accessory', 'wig', 'cosmetics']) {
      if (u.hasModule('refundsale$t')) return true;
    }
    return false;
  }

  bool _anyExchange(AppUser u) {
    for (final t in ['jeans', 'shoes', 'top', 'complete', 'accessory', 'wig', 'cosmetics']) {
      if (u.hasModule('exchangesale$t')) return true;
    }
    return false;
  }

  Widget _tile(
    BuildContext context,
    IconData icon,
    String title,
    String sub,
    Color color,
    String route,
    bool visible, {
    VoidCallback? onTap,
  }) {
    // route used when onTap is null
    if (!visible) return const SizedBox.shrink();
    return Card(
      margin: const EdgeInsets.only(bottom: 10),
      child: ListTile(
        leading: Icon(icon, color: color),
        title: Text(title, style: const TextStyle(fontWeight: FontWeight.w700)),
        subtitle: Text(sub, style: const TextStyle(color: AppColors.textMuted, fontSize: 12)),
        trailing: const Icon(Icons.chevron_right),
        onTap: onTap ?? () => context.push(route),
      ),
    );
  }
}
