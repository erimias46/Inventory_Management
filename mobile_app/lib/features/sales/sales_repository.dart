import '../../core/network/api_client.dart';
import '../../core/utils/json_parse.dart';

class SalesRepository {
  SalesRepository(this._api);

  final ApiClient _api;

  Future<List<Map<String, dynamic>>> productTypes() async {
    final data = await _api.get('/products/types');
    if (data is List) {
      return data.cast<Map<String, dynamic>>();
    }
    return [];
  }

  Future<List<Map<String, dynamic>>> productNames(String type) async {
    final data = await _api.get('/products/names', query: {'type': type});
    if (data is List) return data.cast<Map<String, dynamic>>();
    return [];
  }

  Future<Map<String, dynamic>> productSizes(String type, String name) async {
    final data = await _api.get('/products/sizes', query: {'type': type, 'name': name});
    if (data is Map<String, dynamic>) return data;
    return {};
  }

  Future<double> productPrice(String type, String name, String size) async {
    final data = await _api.get('/products/price', query: {'type': type, 'name': name, 'size': size});
    if (data is Map) return parseJsonDouble(data['price']);
    return 0;
  }

  Future<List<Map<String, dynamic>>> searchAllProducts({String? q}) async {
    final data = await _api.get('/products/search-all', query: {if (q != null && q.isNotEmpty) 'q': q});
    if (data is List) return data.cast<Map<String, dynamic>>();
    return [];
  }

  Future<List<Map<String, dynamic>>> searchMulti(String type, List<String> sizes) async {
    final data = await _api.post('/products/search-multi', body: {'type': type, 'sizes': sizes});
    if (data is List) return data.cast<Map<String, dynamic>>();
    return [];
  }

  Future<List<Map<String, dynamic>>> searchProducts(String type, {String? size, String? q}) async {
    final data = await _api.get('/products/search', query: {
      'type': type,
      if (size != null && size.isNotEmpty) 'size': size,
      if (q != null && q.isNotEmpty) 'q': q,
    });
    if (data is List) return data.cast<Map<String, dynamic>>();
    return [];
  }

  Future<Map<String, dynamic>> submitMultiSale({
    required List<Map<String, dynamic>> lines,
    String method = 'shop',
    String? bankName,
    String? reason,
  }) async {
    final data = await _api.post('/sales/multi', body: {
      'lines': lines,
      'method': method,
      if (bankName != null) 'bank_name': bankName,
      if (reason != null) 'reason': reason,
    });
    if (data is Map<String, dynamic>) return data;
    return {};
  }

  Future<List<Map<String, dynamic>>> listSales({int page = 1}) async {
    final data = await _api.get('/sales', query: {'page': page, 'per_page': 50});
    if (data is Map && data['items'] is List) {
      return (data['items'] as List).cast<Map<String, dynamic>>();
    }
    return [];
  }

  Future<Map<String, dynamic>> getSale(String type, int id) async {
    final data = await _api.get('/sales/$type/$id');
    if (data is Map<String, dynamic>) return data;
    return {};
  }

  Future<void> refundSale(String type, int id) async {
    await _api.post('/sales/$type/$id/refund');
  }

  Future<void> updateSale(
    String type,
    int id, {
    double? price,
    double? cash,
    double? bank,
    String? method,
    String? bankName,
  }) async {
    await _api.put('/sales/$type/$id', body: {
      if (price != null) 'price': price,
      if (cash != null) 'cash': cash,
      if (bank != null) 'bank': bank,
      if (method != null) 'method': method,
      if (bankName != null) 'bank_name': bankName,
    });
  }

  Future<void> deleteSale(String type, int id) async {
    await _api.delete('/sales/$type/$id');
  }

  Future<Map<String, dynamic>> exchangeSale({
    required String type,
    required int salesId,
    required String name,
    required String size,
    required double price,
    double cash = 0,
    double bank = 0,
    String method = 'shop',
    String? bankName,
    int quantity = 1,
  }) async {
    final data = await _api.post('/sales/$type/$salesId/exchange', body: {
      'name': name,
      'size': size,
      'price': price,
      'cash': cash > 0 ? cash : price,
      'bank': bank,
      'method': method,
      if (bankName != null) 'bank_name': bankName,
      'quantity': quantity,
    });
    if (data is Map<String, dynamic>) return data;
    return {};
  }

  Future<List<Map<String, dynamic>>> multiSaleLogs({int limit = 100}) async {
    final data = await _api.get('/sales/logs', query: {'limit': limit});
    if (data is List) return data.cast<Map<String, dynamic>>();
    return [];
  }

  Future<List<Map<String, dynamic>>> listDeliveries() async {
    final data = await _api.get('/delivery');
    if (data is List) return data.cast<Map<String, dynamic>>();
    return [];
  }

  Future<void> completeDelivery(String type, int id) async {
    await _api.post('/delivery/complete', body: {'type': type, 'id': id});
  }

  Future<List<Map<String, dynamic>>> verifyQueue(String type) async {
    final data = await _api.get('/verify/queue', query: {'type': type});
    if (data is List) return data.cast<Map<String, dynamic>>();
    return [];
  }

  Future<void> approveVerify(String type, int id) async {
    await _api.post('/verify/$type/$id/approve', body: {'type': type, 'id': id});
  }

  Future<List<String>> banks() async {
    final data = await _api.get('/banks');
    if (data is List) {
      return uniqueStrings(
        data.map((e) => e is Map ? (e['name'] ?? e['bankname']) : e),
      );
    }
    return [];
  }
}
