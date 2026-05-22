import '../../core/network/api_client.dart';

class OpsRepository {
  OpsRepository(this._api);

  final ApiClient _api;

  Future<List<Map<String, dynamic>>> refundLogs(String type) async {
    final data = await _api.get('/ops/refunds', query: {'type': type});
    if (data is List) return data.cast<Map<String, dynamic>>();
    return [];
  }

  Future<List<Map<String, dynamic>>> exchangeLogs(String type) async {
    final data = await _api.get('/ops/exchanges', query: {'type': type});
    if (data is List) return data.cast<Map<String, dynamic>>();
    return [];
  }

  Future<List<Map<String, dynamic>>> stockLogs(
    String type, {
    String? from,
    String? to,
    String? product,
  }) async {
    final data = await _api.get('/ops/stock-logs', query: {
      'type': type,
      if (from != null) 'from': from,
      if (to != null) 'to': to,
      if (product != null) 'product': product,
    });
    if (data is List) return data.cast<Map<String, dynamic>>();
    return [];
  }

  Future<List<Map<String, dynamic>>> constantConfigs() async {
    final data = await _api.get('/ops/constants');
    if (data is List) return data.cast<Map<String, dynamic>>();
    return [];
  }

  Future<Map<String, dynamic>> constantRows(int configId) async {
    final data = await _api.get('/ops/constants/$configId');
    if (data is Map<String, dynamic>) return data;
    return {};
  }

  Future<void> addConstantRow(int configId, Map<String, dynamic> row) async {
    await _api.post('/ops/constants/$configId', body: {'row': row});
  }

  Future<void> deleteConstantRow(int configId, String primaryKey, dynamic id) async {
    await _api.delete('/ops/constants/$configId', query: {'primary_key': primaryKey, 'id': id});
  }

  Future<Map<String, dynamic>> settings() async {
    final data = await _api.get('/ops/settings');
    if (data is Map<String, dynamic>) return data;
    return {};
  }

  Future<Map<String, dynamic>> updateSettings(Map<String, dynamic> body) async {
    final data = await _api.put('/ops/settings', body: body);
    if (data is Map<String, dynamic>) return data;
    return {};
  }

  Future<List<Map<String, dynamic>>> backups() async {
    final data = await _api.get('/ops/backups');
    if (data is List) return data.cast<Map<String, dynamic>>();
    return [];
  }

  Future<Map<String, dynamic>> createBackup() async {
    final data = await _api.post('/ops/backups');
    if (data is Map<String, dynamic>) return data;
    return {};
  }

  Future<Map<String, dynamic>> exportTable(String table, {String? from, String? to}) async {
    final data = await _api.get('/ops/export', query: {
      'table': table,
      if (from != null) 'from': from,
      if (to != null) 'to': to,
    });
    if (data is Map<String, dynamic>) return data;
    return {};
  }

  Future<List<Map<String, dynamic>>> emails() async {
    final data = await _api.get('/ops/emails');
    if (data is List) return data.cast<Map<String, dynamic>>();
    return [];
  }

  Future<void> addEmail(String email) async {
    await _api.post('/ops/emails', body: {'email': email});
  }

  Future<void> deleteEmail(int id) async {
    await _api.delete('/ops/emails/$id');
  }

  Future<List<Map<String, dynamic>>> saleItemLog() async {
    final data = await _api.get('/ops/sale-log');
    if (data is List) return data.cast<Map<String, dynamic>>();
    return [];
  }

  Future<List<Map<String, dynamic>>> productsInLog() async {
    final data = await _api.get('/ops/products-in');
    if (data is List) return data.cast<Map<String, dynamic>>();
    return [];
  }

  Future<List<Map<String, dynamic>>> allProductTypes() async {
    final data = await _api.get('/ops/all-product-types');
    if (data is List) return data.cast<Map<String, dynamic>>();
    return [];
  }

  Future<List<Map<String, dynamic>>> digitalPages() async {
    final data = await _api.get('/ops/digital-pages');
    if (data is List) return data.cast<Map<String, dynamic>>();
    return [];
  }
}
