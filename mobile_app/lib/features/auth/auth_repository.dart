import '../../core/models/user_model.dart';
import '../../core/models/shop_info.dart';
import '../../core/network/api_client.dart';

class HealthStatus {
  HealthStatus({required this.apiOk, required this.dbConnected, this.message});

  final bool apiOk;
  final bool dbConnected;
  final String? message;

  bool get fullyConnected => apiOk && dbConnected;
}

class AuthRepository {
  AuthRepository(this._api);

  final ApiClient _api;

  Future<List<ShopInfo>> fetchShops() async {
    try {
      final data = await _api.get('/shops');
      if (data is List) {
        return data.cast<Map<String, dynamic>>().map(ShopInfo.fromJson).toList();
      }
    } catch (_) {}
    return [];
  }

  Future<AppUser> login(String username, String password, {String? shopSlug}) async {
    final data = await _api.post('/auth/login', body: {
      'username': username,
      'password': password,
      if (shopSlug != null && shopSlug.isNotEmpty) 'shop_slug': shopSlug,
    });
    if (data is! Map<String, dynamic>) {
      throw Exception('Invalid login response');
    }
    final token = data['token']?.toString() ?? '';
    await _api.setToken(token);
    await _api.resetClient();
    final shopMap = data['shop'] as Map<String, dynamic>?;
    return AppUser.fromJson(data['user'] as Map<String, dynamic>, shop: shopMap);
  }

  Future<AppUser?> me() async {
    final token = await _api.getToken();
    if (token == null || token.isEmpty) return null;
    try {
      final data = await _api.get('/auth/me');
      if (data is Map<String, dynamic>) {
        return AppUser.fromJson(data['user'] as Map<String, dynamic>);
      }
      return null;
    } catch (_) {
      await _api.clearToken();
      return null;
    }
  }

  Future<void> logout() async {
    try {
      await _api.post('/auth/logout');
    } catch (_) {}
    await _api.clearToken();
  }

  Future<HealthStatus> healthCheck() async {
    try {
      final data = await _api.get('/health');
      if (data is Map<String, dynamic>) {
        final db = data['database']?.toString() == 'connected';
        return HealthStatus(apiOk: true, dbConnected: db, message: db ? 'API and database OK' : 'API OK but database disconnected');
      }
      return HealthStatus(apiOk: true, dbConnected: false, message: 'API responded');
    } catch (e) {
      return HealthStatus(apiOk: false, dbConnected: false, message: e.toString());
    }
  }

  /// Verifies API + auth + database read (products from stock tables).
  Future<HealthStatus> connectionDiagnostics() async {
    final base = await healthCheck();
    if (!base.apiOk) return base;

    final token = await _api.getToken();
    if (token == null || token.isEmpty) {
      return HealthStatus(apiOk: true, dbConnected: base.dbConnected, message: 'API OK — log in to test data access');
    }

    try {
      final types = await _api.get('/products/types');
      final count = types is List ? types.length : 0;
      return HealthStatus(
        apiOk: true,
        dbConnected: base.dbConnected && count > 0,
        message: 'Connected — $count categories loaded from database',
      );
    } catch (e) {
      return HealthStatus(
        apiOk: true,
        dbConnected: false,
        message: 'API OK but data fetch failed: $e',
      );
    }
  }
}
