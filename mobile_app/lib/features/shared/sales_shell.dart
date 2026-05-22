import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../core/models/user_model.dart';
import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../sales/pos_screen.dart';
import '../sales/all_sales_screen.dart';
import '../sales/multi_search_screen.dart';
import '../sales/sale_item_log_screen.dart';
import '../sales/products_in_log_screen.dart';
import '../sales/all_product_types_screen.dart';
import '../sales/delivery_screen.dart';
import '../sales/verify_screen.dart';
import 'connection_banner.dart';
import 'pos_widgets.dart';

class SalesShell extends ConsumerStatefulWidget {
  const SalesShell({super.key});

  @override
  ConsumerState<SalesShell> createState() => _SalesShellState();
}

class _SalesShellState extends ConsumerState<SalesShell> {
  int _index = 0;

  @override
  Widget build(BuildContext context) {
    final user = ref.watch(currentUserProvider)!;

    final pages = <Widget>[
      const _PosTab(),
      if (user.hasModule('allsale')) const AllSalesScreen(embedded: true),
      if (user.hasModule('searchproduct')) const MultiSearchScreen(embedded: true),
      _MoreTab(user: user),
    ];

    final destinations = <NavigationDestination>[
      const NavigationDestination(icon: Icon(Icons.point_of_sale_outlined), selectedIcon: Icon(Icons.point_of_sale), label: 'POS'),
      if (user.hasModule('allsale'))
        const NavigationDestination(icon: Icon(Icons.receipt_long_outlined), selectedIcon: Icon(Icons.receipt_long), label: 'Sales'),
      if (user.hasModule('searchproduct'))
        const NavigationDestination(icon: Icon(Icons.search_outlined), selectedIcon: Icon(Icons.search), label: 'Search'),
      const NavigationDestination(icon: Icon(Icons.apps_outlined), selectedIcon: Icon(Icons.apps), label: 'More'),
    ];

    final safeIndex = _index.clamp(0, pages.length - 1);

    return Scaffold(
      backgroundColor: AppColors.navy,
      appBar: AppBar(
        title: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                gradient: const LinearGradient(colors: [AppColors.accent, Color(0xFF8B5CF6)]),
                borderRadius: BorderRadius.circular(10),
              ),
              child: const Icon(Icons.storefront, size: 20),
            ),
            const SizedBox(width: 12),
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text('Yurostock', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w800)),
                Text(user.userName, style: const TextStyle(fontSize: 11, color: AppColors.textMuted)),
              ],
            ),
          ],
        ),
        actions: [
          if (user.isMasterAdmin)
            IconButton(
              tooltip: 'Admin',
              icon: const Icon(Icons.admin_panel_settings_outlined),
              onPressed: () => context.go('/admin'),
            ),
          IconButton(icon: const Icon(Icons.settings_outlined), onPressed: () => context.push('/settings')),
        ],
      ),
      body: Column(
        children: [
          const ConnectionBanner(),
          Expanded(child: IndexedStack(index: safeIndex, children: pages)),
        ],
      ),
      bottomNavigationBar: NavigationBar(
        selectedIndex: safeIndex,
        onDestinationSelected: (i) => setState(() => _index = i),
        destinations: destinations,
      ),
    );
  }
}

class _PosTab extends StatelessWidget {
  const _PosTab();

  @override
  Widget build(BuildContext context) {
    return const Column(
      crossAxisAlignment: CrossAxisAlignment.stretch,
      children: [
        PosHeader(title: 'Point of Sale', subtitle: 'Tap a product to add to cart'),
        Expanded(child: PosScreen()),
      ],
    );
  }
}

/// Per-category sale (web sale_jeans.php, sale_shoes.php, …).
class CategoryPosScreen extends StatelessWidget {
  const CategoryPosScreen({super.key, required this.type});

  final String type;

  @override
  Widget build(BuildContext context) {
    final label = '${type[0].toUpperCase()}${type.substring(1)}';
    return Scaffold(
      appBar: AppBar(title: Text('$label Sale')),
      body: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          PosHeader(title: '$label POS', subtitle: 'Dedicated sale for $label'),
          Expanded(child: PosScreen(fixedCategory: type)),
        ],
      ),
    );
  }
}

void _showCategorySales(BuildContext context) {
  const types = [
    ('jeans', 'Jeans'),
    ('shoes', 'Shoes'),
    ('top', 'Top'),
    ('complete', 'Complete'),
    ('accessory', 'Accessory'),
    ('wig', 'Wig'),
    ('cosmetics', 'Cosmetics'),
  ];
  showModalBottomSheet(
    context: context,
    backgroundColor: AppColors.navyLight,
    builder: (ctx) => SafeArea(
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: types
            .map(
              (t) => ListTile(
                title: Text('${t.$2} Sale'),
                trailing: const Icon(Icons.chevron_right),
                onTap: () {
                  Navigator.pop(ctx);
                  context.push('/sales/category/${t.$1}');
                },
              ),
            )
            .toList(),
      ),
    ),
  );
}

bool _hasRefundModule(AppUser user) {
  for (final t in ['jeans', 'shoes', 'top', 'complete', 'accessory', 'wig', 'cosmetics']) {
    if (user.hasModule('refundsale$t')) return true;
  }
  return false;
}

class _MoreTab extends ConsumerWidget {
  const _MoreTab({required this.user});

  final AppUser user;

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        const PosHeader(title: 'More', subtitle: 'Tools & operations'),
        if (user.hasModule('deliverysale'))
          _MoreCard(
            icon: Icons.local_shipping_outlined,
            title: 'Delivery',
            subtitle: 'Pending & complete deliveries',
            color: AppColors.warning,
            onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const DeliveryScreen())),
          ),
        if (user.hasModule('verifyproducts') || user.isMasterAdmin)
          _MoreCard(
            icon: Icons.verified_outlined,
            title: 'Verify Stock',
            subtitle: 'Approve incoming inventory',
            color: AppColors.success,
            onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const VerifyScreen())),
          ),
        if (user.hasModule('logsale') || user.isMasterAdmin) ...[
          _MoreCard(
            icon: Icons.history,
            title: 'Sale Log',
            subtitle: 'Per-item sale history',
            color: AppColors.accent,
            onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const SaleItemLogScreen())),
          ),
          _MoreCard(
            icon: Icons.receipt_long,
            title: 'Multi Sale Logs',
            subtitle: 'Batch sale history',
            color: AppColors.accentBright,
            onTap: () => context.push('/sales/logs'),
          ),
          _MoreCard(
            icon: Icons.inventory_2_outlined,
            title: 'Products In',
            subtitle: 'Stock added over time',
            color: AppColors.success,
            onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const ProductsInLogScreen())),
          ),
          _MoreCard(
            icon: Icons.category_outlined,
            title: 'All Product Types',
            subtitle: 'Cross-category summary',
            color: AppColors.warning,
            onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const AllProductTypesScreen())),
          ),
        ],
        _MoreCard(
          icon: Icons.point_of_sale_outlined,
          title: 'Category Sales',
          subtitle: 'Jeans, shoes, top… dedicated POS',
          color: AppColors.posGreen,
          onTap: () => _showCategorySales(context),
        ),
        if (user.isMasterAdmin || _hasRefundModule(user))
          _MoreCard(
            icon: Icons.undo,
            title: 'Refund Logs',
            subtitle: 'Refunded sales by category',
            color: AppColors.danger,
            onTap: () => context.push('/admin/logs/refund'),
          ),
        _MoreCard(
          icon: Icons.logout,
          title: 'Sign Out',
          subtitle: 'End session',
          color: AppColors.danger,
          onTap: () async {
            await ref.read(authRepositoryProvider).logout();
            ref.read(currentUserProvider.notifier).state = null;
            if (context.mounted) context.go('/login');
          },
        ),
      ],
    );
  }
}

class _MoreCard extends StatelessWidget {
  const _MoreCard({
    required this.icon,
    required this.title,
    required this.subtitle,
    required this.color,
    required this.onTap,
  });

  final IconData icon;
  final String title;
  final String subtitle;
  final Color color;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      child: ListTile(
        leading: Container(
          padding: const EdgeInsets.all(10),
          decoration: BoxDecoration(color: color.withValues(alpha: 0.2), borderRadius: BorderRadius.circular(12)),
          child: Icon(icon, color: color),
        ),
        title: Text(title, style: const TextStyle(fontWeight: FontWeight.w700)),
        subtitle: Text(subtitle, style: const TextStyle(color: AppColors.textMuted, fontSize: 12)),
        trailing: const Icon(Icons.chevron_right, color: AppColors.textMuted),
        onTap: onTap,
      ),
    );
  }
}
