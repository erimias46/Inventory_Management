import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

import '../network/api_client.dart';
import '../models/user_model.dart';
import '../models/app_category.dart';
import '../models/shop_info.dart';
import '../../features/auth/auth_repository.dart';
import '../../features/sales/sales_repository.dart';
import '../../features/admin/admin_repository.dart';
import '../../features/admin/ops_repository.dart';

final secureStorageProvider = Provider<FlutterSecureStorage>((_) => const FlutterSecureStorage());

final apiClientProvider = Provider<ApiClient>((ref) {
  return ApiClient(ref.watch(secureStorageProvider));
});

final authRepositoryProvider = Provider<AuthRepository>((ref) {
  return AuthRepository(ref.watch(apiClientProvider));
});

final salesRepositoryProvider = Provider<SalesRepository>((ref) {
  return SalesRepository(ref.watch(apiClientProvider));
});

final adminRepositoryProvider = Provider<AdminRepository>((ref) {
  return AdminRepository(ref.watch(apiClientProvider));
});

final opsRepositoryProvider = Provider<OpsRepository>((ref) {
  return OpsRepository(ref.watch(apiClientProvider));
});

final currentUserProvider = StateProvider<AppUser?>((ref) => null);

final authLoadingProvider = StateProvider<bool>((ref) => false);

/// Fetches categories from the API. Depends on auth state so it refreshes after login.
final categoriesProvider = FutureProvider<List<AppCategory>>((ref) async {
  final user = ref.watch(currentUserProvider);
  if (user == null) return [];
  return ref.read(adminRepositoryProvider).fetchCategories();
});

/// Fetches available shops for the login screen (public endpoint, no auth required).
final shopsProvider = FutureProvider<List<ShopInfo>>((ref) async {
  return ref.read(authRepositoryProvider).fetchShops();
});
