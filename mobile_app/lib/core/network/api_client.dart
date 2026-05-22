import 'package:dio/dio.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

import '../config/api_config.dart';
import 'api_exception.dart';

class ApiClient {
  ApiClient(this._storage);

  final FlutterSecureStorage _storage;
  static const _tokenKey = 'auth_token';
  Dio? _dio;

  Future<Dio> get dio async {
    if (_dio != null) return _dio!;
    final baseUrl = await ApiConfig.getBaseUrl();
    _dio = Dio(BaseOptions(
      baseUrl: baseUrl,
      connectTimeout: const Duration(seconds: 30),
      receiveTimeout: const Duration(seconds: 30),
      headers: {'Accept': 'application/json', 'Content-Type': 'application/json'},
    ));
    _dio!.interceptors.add(InterceptorsWrapper(
      onRequest: (options, handler) async {
        final token = await _storage.read(key: _tokenKey);
        if (token != null && token.isNotEmpty) {
          options.headers['Authorization'] = 'Bearer $token';
          options.headers['X-Api-Token'] = token;
        }
        handler.next(options);
      },
      onError: (error, handler) async {
        if (error.response?.statusCode == 401) {
          await clearToken();
        }
        handler.next(error);
      },
    ));
    return _dio!;
  }

  Future<void> resetClient() async {
    _dio = null;
  }

  Future<String?> getToken() => _storage.read(key: _tokenKey);

  Future<void> setToken(String token) => _storage.write(key: _tokenKey, value: token);

  Future<void> clearToken() => _storage.delete(key: _tokenKey);

  Future<dynamic> get(String path, {Map<String, dynamic>? query}) async {
    final client = await dio;
    final params = Map<String, dynamic>.from(query ?? {});
    final token = await _storage.read(key: _tokenKey);
    if (token != null && token.isNotEmpty) {
      params['token'] = token;
    }
    try {
      final res = await client.get(path, queryParameters: params);
      return _parse(res);
    } on DioException catch (e) {
      throw _fromDio(e);
    }
  }

  Future<dynamic> post(String path, {Map<String, dynamic>? body}) async {
    final client = await dio;
    final headers = <String, dynamic>{};
    final token = await _storage.read(key: _tokenKey);
    if (token != null && token.isNotEmpty) {
      headers['X-Api-Token'] = token;
    }
    try {
      final res = await client.post(path, data: body, options: Options(headers: headers));
      return _parse(res);
    } on DioException catch (e) {
      throw _fromDio(e);
    }
  }

  Future<dynamic> put(String path, {Map<String, dynamic>? body}) async {
    final client = await dio;
    try {
      final res = await client.put(path, data: body);
      return _parse(res);
    } on DioException catch (e) {
      throw _fromDio(e);
    }
  }

  Future<dynamic> delete(String path, {Map<String, dynamic>? body, Map<String, dynamic>? query}) async {
    final client = await dio;
    final params = Map<String, dynamic>.from(query ?? {});
    final token = await _storage.read(key: _tokenKey);
    if (token != null && token.isNotEmpty) {
      params['token'] = token;
    }
    try {
      final res = await client.delete(path, data: body, queryParameters: params.isEmpty ? null : params);
      return _parse(res);
    } on DioException catch (e) {
      throw _fromDio(e);
    }
  }

  dynamic _parse(Response res) {
    final data = res.data;
    if (data is! Map<String, dynamic>) {
      throw ApiException('Invalid response format');
    }
    if (data['ok'] == true) {
      return data['data'];
    }
    final err = data['error'];
    if (err is Map) {
      throw ApiException(
        err['message']?.toString() ?? 'Request failed',
        code: err['code']?.toString(),
        statusCode: res.statusCode,
      );
    }
    throw ApiException('Request failed', statusCode: res.statusCode);
  }

  ApiException _fromDio(DioException e) {
    final data = e.response?.data;
    if (data is Map && data['error'] is Map) {
      final err = data['error'] as Map;
      return ApiException(
        err['message']?.toString() ?? e.message ?? 'Network error',
        code: err['code']?.toString(),
        statusCode: e.response?.statusCode,
      );
    }
    if (e.type == DioExceptionType.connectionTimeout ||
        e.type == DioExceptionType.receiveTimeout ||
        e.type == DioExceptionType.connectionError) {
      return ApiException(
        'Cannot reach the server. Open API Settings and use '
        'http://127.0.0.1:8888/stock/api/v1/index.php (iOS) or ensure MAMP is running.',
        statusCode: e.response?.statusCode,
      );
    }
    return ApiException(e.message ?? 'Network error', statusCode: e.response?.statusCode);
  }
}
