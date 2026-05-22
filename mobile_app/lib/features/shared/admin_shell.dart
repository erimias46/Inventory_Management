import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../core/models/user_model.dart';
import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../admin/dashboard_screen.dart';
import '../admin/categories_screen.dart';
import '../admin/users_screen.dart';
import '../admin/customers_screen.dart';
import 'connection_banner.dart';
import 'pos_widgets.dart';

class AdminShell extends ConsumerStatefulWidget {
  const AdminShell({super.key});

  @override
  ConsumerState<AdminShell> createState() => _AdminShellState();
}

class _AdminShellState extends ConsumerState<AdminShell> {
  int _index = 0;

  @override
  Widget build(BuildContext context) {
    final user = ref.watch(currentUserProvider)!;

    final pages = <Widget>[
      const DashboardScreen(embedded: true),
      const CategoriesScreen(embedded: true),
      _AdminMoreTab(user: user),
    ];

    return Scaffold(
      backgroundColor: AppColors.navy,
      appBar: AppBar(
        title: const Text('Admin'),
        actions: [
          IconButton(
            tooltip: 'Sales POS',
            icon: const Icon(Icons.point_of_sale_outlined),
            onPressed: () => context.go('/sales'),
          ),
          IconButton(icon: const Icon(Icons.settings_outlined), onPressed: () => context.push('/settings')),
        ],
      ),
      body: Column(
        children: [
          const ConnectionBanner(),
          Expanded(child: IndexedStack(index: _index.clamp(0, pages.length - 1), children: pages)),
        ],
      ),
      bottomNavigationBar: NavigationBar(
        selectedIndex: _index,
        onDestinationSelected: (i) => setState(() => _index = i),
        destinations: const [
          NavigationDestination(icon: Icon(Icons.dashboard_outlined), selectedIcon: Icon(Icons.dashboard), label: 'Dashboard'),
          NavigationDestination(icon: Icon(Icons.category_outlined), selectedIcon: Icon(Icons.category), label: 'Inventory'),
          NavigationDestination(icon: Icon(Icons.more_horiz), selectedIcon: Icon(Icons.more_horiz), label: 'More'),
        ],
      ),
    );
  }
}

class _AdminMoreTab extends ConsumerWidget {
  const _AdminMoreTab({required this.user});

  final AppUser user;

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        const PosHeader(title: 'Admin Tools', subtitle: 'Users, logs, backup & settings'),
        Card(
          margin: const EdgeInsets.only(bottom: 12),
          child: ListTile(
            leading: const Icon(Icons.build_circle_outlined, color: AppColors.posGreen),
            title: const Text('Operations', style: TextStyle(fontWeight: FontWeight.w700)),
            subtitle: const Text('Refund logs, backup, constants, export…', style: TextStyle(color: AppColors.textMuted, fontSize: 12)),
            trailing: const Icon(Icons.chevron_right),
            onTap: () => context.push('/admin/tools'),
          ),
        ),
        if (user.hasModule('user'))
          Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ListTile(
              leading: const Icon(Icons.people_outline, color: AppColors.accent),
              title: const Text('Users', style: TextStyle(fontWeight: FontWeight.w700)),
              subtitle: const Text('Manage staff accounts', style: TextStyle(color: AppColors.textMuted, fontSize: 12)),
              trailing: const Icon(Icons.chevron_right),
              onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const UsersScreen())),
            ),
          ),
        if (user.hasModule('custview'))
          Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ListTile(
              leading: const Icon(Icons.person_outline, color: AppColors.warning),
              title: const Text('Customers', style: TextStyle(fontWeight: FontWeight.w700)),
              subtitle: const Text('Customer directory', style: TextStyle(color: AppColors.textMuted, fontSize: 12)),
              trailing: const Icon(Icons.chevron_right),
              onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const CustomersScreen())),
            ),
          ),
        Card(
          margin: const EdgeInsets.only(bottom: 12),
          child: ListTile(
            leading: const Icon(Icons.logout, color: AppColors.danger),
            title: const Text('Sign Out', style: TextStyle(fontWeight: FontWeight.w700)),
            onTap: () async {
              await ref.read(authRepositoryProvider).logout();
              ref.read(currentUserProvider.notifier).state = null;
              if (context.mounted) context.go('/login');
            },
          ),
        ),
      ],
    );
  }
}
