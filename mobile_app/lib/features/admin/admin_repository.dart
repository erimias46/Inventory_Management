import '../../core/network/api_client.dart';

class AdminRepository {
  AdminRepository(this._api);

  final ApiClient _api;

  Future<Map<String, dynamic>> dashboardSummary({String period = '30'}) async {
    final data = await _api.get('/dashboard/summary', query: {'period': period});
    if (data is Map<String, dynamic>) return data;
    return {};
  }

  Future<Map<String, dynamic>> dailySales({required int month, required int year}) async {
    final data = await _api.get('/dashboard/daily-sales', query: {'month': month, 'year': year});
    if (data is Map<String, dynamic>) return data;
    return {};
  }

  Future<List<Map<String, dynamic>>> inventory(String type, {String? q, int page = 1}) async {
    final data = await _api.get('/inventory/$type', query: {
      if (q != null && q.isNotEmpty) 'q': q,
      'page': page,
      'per_page': 50,
    });
    if (data is List) return data.cast<Map<String, dynamic>>();
    return [];
  }

  Future<Map<String, dynamic>> inventoryItem(String type, int id) async {
    final data = await _api.get('/inventory/$type/$id');
    if (data is Map<String, dynamic>) return data;
    return {};
  }

  Future<void> updateInventory(String type, int id, {double? price, double? quantity, double? buyPrice}) async {
    await _api.put('/inventory/$type/$id', body: {
      if (price != null) 'price': price,
      if (quantity != null) 'quantity': quantity,
      if (buyPrice != null) 'buy_price': buyPrice,
    });
  }

  Future<void> deleteInventory(String type, int id) async {
    await _api.delete('/inventory/$type/$id');
  }

  Future<Map<String, dynamic>> createInventory(
    String type, {
    required String name,
    required String size,
    required double price,
    required double quantity,
    int? sizeId,
    int? typeId,
    String? typeLabel,
  }) async {
    final data = await _api.post('/inventory/$type', body: {
      'name': name,
      'size': size,
      'price': price,
      'quantity': quantity,
      if (sizeId != null) 'size_id': sizeId,
      if (typeId != null) 'type_id': typeId,
      if (typeLabel != null) 'type': typeLabel,
    });
    if (data is Map<String, dynamic>) return data;
    return {};
  }

  Future<List<Map<String, dynamic>>> inventoryTypes(String type) async {
    final data = await _api.get('/inventory/$type/types');
    if (data is List) return data.cast<Map<String, dynamic>>();
    return [];
  }

  Future<List<Map<String, dynamic>>> users() async {
    final data = await _api.get('/users');
    if (data is List) return data.cast<Map<String, dynamic>>();
    return [];
  }

  Future<Map<String, dynamic>> getUser(int id) async {
    final data = await _api.get('/users/$id');
    if (data is Map<String, dynamic>) return data;
    return {};
  }

  Future<List<Map<String, dynamic>>> userModuleKeys() async {
    final data = await _api.get('/users/module-keys');
    if (data is List) return data.cast<Map<String, dynamic>>();
    return [];
  }

  Future<int> createUser({
    required String userName,
    required String password,
    String privilege = 'user',
    Map<String, dynamic>? modules,
  }) async {
    final data = await _api.post('/users', body: {
      'user_name': userName,
      'password': password,
      'privilege': privilege,
      'modules': modules ?? {},
    });
    if (data is Map && data['id'] != null) return (data['id'] as num).toInt();
    return 0;
  }

  Future<void> updateUser(int id, Map<String, dynamic> body) async {
    await _api.put('/users/$id', body: body);
  }

  Future<void> deleteUser(int id) async {
    await _api.delete('/users/$id');
  }

  Future<Map<String, dynamic>> updateProfile({
    required String userName,
    required String password,
  }) async {
    final data = await _api.put('/users/me/profile', body: {
      'user_name': userName,
      'password': password,
    });
    if (data is Map && data['user'] is Map) return (data['user'] as Map).cast<String, dynamic>();
    return {};
  }

  Future<List<Map<String, dynamic>>> customers() async {
    final data = await _api.get('/customers/manage');
    if (data is List) return data.cast<Map<String, dynamic>>();
    return [];
  }
}
