import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../providers/app_providers.dart';
import '../../features/auth/login_screen.dart';
import '../../features/settings/settings_screen.dart';
import '../../features/shared/sales_shell.dart' show SalesShell, CategoryPosScreen;
import '../../features/shared/admin_shell.dart';
import '../../features/sales/sale_detail_screen.dart';
import '../../features/sales/exchange_screen.dart';
import '../../features/sales/sales_logs_screen.dart';
import '../../features/admin/inventory_list_screen.dart';
import '../../features/admin/add_product_screen.dart';
import '../../features/admin/admin_tools_screen.dart';
import '../../features/admin/category_logs_screen.dart';
import '../../features/admin/constants_screen.dart';
import '../../features/admin/backup_screen.dart';
import '../../features/admin/export_screen.dart';
import '../../features/admin/email_screen.dart';
import '../../features/admin/store_settings_screen.dart';
import '../../features/admin/digital_pages_screen.dart';
import '../../features/admin/edit_price_screen.dart';
import '../../features/sales/sale_edit_screen.dart';
import '../../features/sales/sale_item_log_screen.dart';
import '../../features/sales/products_in_log_screen.dart';
import '../../features/sales/all_product_types_screen.dart';
import '../../features/admin/user_edit_screen.dart';
import '../../features/auth/profile_screen.dart';

final routerProvider = Provider<GoRouter>((ref) {
  final user = ref.watch(currentUserProvider);

  return GoRouter(
    initialLocation: '/login',
    redirect: (context, state) {
      final loggedIn = user != null;
      final onLogin = state.matchedLocation == '/login';
      final onSettings = state.matchedLocation == '/settings';
      if (!loggedIn && !onLogin && !onSettings) return '/login';
      if (loggedIn && onLogin) {
        return user.isMasterAdmin ? '/admin' : '/sales';
      }
      return null;
    },
    routes: [
      GoRoute(path: '/login', builder: (_, __) => const LoginScreen()),
      GoRoute(path: '/settings', builder: (_, __) => const SettingsScreen()),
      GoRoute(path: '/sales', builder: (_, __) => const SalesShell()),
      GoRoute(
        path: '/sales/detail/:type/:id',
        builder: (_, state) => SaleDetailScreen(
          type: state.pathParameters['type']!,
          id: int.parse(state.pathParameters['id']!),
        ),
      ),
      GoRoute(
        path: '/sales/exchange/:type/:id',
        builder: (_, state) => ExchangeScreen(
          type: state.pathParameters['type']!,
          salesId: int.parse(state.pathParameters['id']!),
        ),
      ),
      GoRoute(path: '/sales/logs', builder: (_, __) => const SalesLogsScreen()),
      GoRoute(path: '/sales/sale-log', builder: (_, __) => const SaleItemLogScreen()),
      GoRoute(path: '/sales/products-in', builder: (_, __) => const ProductsInLogScreen()),
      GoRoute(path: '/sales/all-product-types', builder: (_, __) => const AllProductTypesScreen()),
      GoRoute(
        path: '/sales/category/:type',
        builder: (_, state) => CategoryPosScreen(type: state.pathParameters['type']!),
      ),
      GoRoute(
        path: '/sales/edit/:type/:id',
        builder: (_, state) => SaleEditScreen(
          type: state.pathParameters['type']!,
          id: int.parse(state.pathParameters['id']!),
        ),
      ),
      GoRoute(path: '/admin', builder: (_, __) => const AdminShell()),
      GoRoute(
        path: '/admin/inventory/:type',
        builder: (_, state) => InventoryListScreen(type: state.pathParameters['type']!),
      ),
      GoRoute(
        path: '/admin/inventory/:type/add',
        builder: (_, state) => AddProductScreen(type: state.pathParameters['type']!),
      ),
      GoRoute(
        path: '/admin/inventory/:type/edit/:id',
        builder: (_, state) => EditPriceScreen(
          type: state.pathParameters['type']!,
          id: int.parse(state.pathParameters['id']!),
        ),
      ),
      GoRoute(path: '/admin/tools', builder: (_, __) => const AdminToolsScreen()),
      GoRoute(path: '/admin/logs/refund', builder: (_, __) => const CategoryLogsScreen(logKind: 'refund')),
      GoRoute(path: '/admin/logs/exchange', builder: (_, __) => const CategoryLogsScreen(logKind: 'exchange')),
      GoRoute(path: '/admin/logs/stock', builder: (_, __) => const CategoryLogsScreen(logKind: 'stock')),
      GoRoute(path: '/admin/constants', builder: (_, __) => const ConstantsHubScreen()),
      GoRoute(
        path: '/admin/constants/:id',
        builder: (_, state) => ConstantTableScreen(configId: int.parse(state.pathParameters['id']!)),
      ),
      GoRoute(path: '/admin/backup', builder: (_, __) => const BackupScreen()),
      GoRoute(path: '/admin/export', builder: (_, __) => const ExportScreen()),
      GoRoute(path: '/admin/emails', builder: (_, __) => const EmailSubscribersScreen()),
      GoRoute(path: '/admin/store-settings', builder: (_, __) => const StoreSettingsScreen()),
      GoRoute(path: '/admin/digital', builder: (_, __) => const DigitalPagesScreen()),
      GoRoute(path: '/admin/users/new', builder: (_, __) => const UserEditScreen()),
      GoRoute(
        path: '/admin/users/:id',
        builder: (_, state) => UserEditScreen(userId: int.parse(state.pathParameters['id']!)),
      ),
      GoRoute(path: '/profile', builder: (_, __) => const ProfileScreen()),
    ],
  );
});
