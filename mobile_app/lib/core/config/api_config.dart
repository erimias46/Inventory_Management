import 'dart:io' show Platform;

import 'package:flutter/foundation.dart' show kIsWeb;
import 'package:shared_preferences/shared_preferences.dart';

class ApiConfig {
  /// MAMP without mod_rewrite: all routes go through index.php + PATH_INFO.
  static const androidBase = 'http://10.0.2.2:8888/stock/api/v1/index.php';
  static const iosBase = 'http://127.0.0.1:8888/stock/api/v1/index.php';
  static const prodBase = 'https://inventory.yurostock.com/api/v1/index.php';
  static const prefsKey = 'api_base_url';

  static String defaultForPlatform() {
    if (kIsWeb) return iosBase;
    if (Platform.isAndroid) return androidBase;
    return iosBase;
  }

  static Future<String> getBaseUrl() async {
    final prefs = await SharedPreferences.getInstance();
    final saved = prefs.getString(prefsKey);
    if (saved != null && saved.isNotEmpty) {
      return _normalize(saved);
    }
    return defaultForPlatform();
  }

  static Future<void> setBaseUrl(String url) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(prefsKey, _normalize(url));
  }

  static String _normalize(String url) {
    var u = url.trim();
    if (u.endsWith('/')) {
      u = u.substring(0, u.length - 1);
    }
    // Migrate old URLs that omitted index.php (break on MAMP without mod_rewrite).
    if (u.endsWith('/api/v1') && !u.endsWith('index.php')) {
      u = '$u/index.php';
    }
    return u;
  }

  /// Stock web root (no /api/v1/...) — used for product image URLs.
  static String stockBaseFromApiUrl(String apiBase) {
    const suffixes = [
      '/api/v1/index.php',
      '/api/v1',
    ];
    for (final suffix in suffixes) {
      if (apiBase.endsWith(suffix)) {
        return apiBase.substring(0, apiBase.length - suffix.length);
      }
    }
    return apiBase;
  }

  /// Returns the default image filename for a category slug.
  /// For well-known slugs the original filenames are preserved;
  /// new dynamic categories fall back to `default{slug}.jpg`.
  static String defaultImageFilename(String category) {
    const known = {
      'jeans': 'defaultjeans.jpg',
      'shoes': 'defaultshoes.jpg',
      'top': 'defaulttop.jpg',
      'complete': 'defaultcomplete.jpg',
      'accessory': 'defaultaccessory.jpg',
      'wig': 'defaultwig.jpg',
      'cosmetics': 'defaultcosmetics.jpg',
    };
    return known[category] ?? 'default${category.replaceAll(RegExp(r'[^a-z0-9]'), '')}.jpg';
  }

  /// Align image host/path with the configured API URL (MAMP + include/uploads).
  static String? normalizeProductImageUrl(
    String? url,
    String apiBase, {
    String? category,
  }) {
    final stockBase = stockBaseFromApiUrl(apiBase);
    if (url == null || url.trim().isEmpty) {
      if (category == null) return null;
      return '$stockBase/include/uploads/${defaultImageFilename(category)}';
    }

    var u = url.trim();
    final apiUri = Uri.tryParse(apiBase);
    if (apiUri != null && apiUri.host.isNotEmpty) {
      final host = apiUri.host;
      final port = apiUri.hasPort ? ':${apiUri.port}' : '';
      u = u
          .replaceFirst('://localhost$port/', '://$host$port/')
          .replaceFirst('://localhost/', '://$host/');
    }

    if (u.contains('/stock/uploads/') && !u.contains('/include/uploads/')) {
      u = u.replaceFirst('/stock/uploads/', '/stock/include/uploads/');
    } else if (u.contains('/uploads/') && !u.contains('/include/uploads/')) {
      final idx = u.indexOf('/uploads/');
      if (idx > 0) {
        u = '${u.substring(0, idx)}/include${u.substring(idx)}';
      }
    }

    final uri = Uri.tryParse(u);
    if (uri != null && uri.hasScheme && uri.pathSegments.isNotEmpty) {
      final encodedPath = '/${uri.pathSegments.map(Uri.encodeComponent).join('/')}';
      return uri.replace(path: encodedPath).toString();
    }

    return u;
  }
}
