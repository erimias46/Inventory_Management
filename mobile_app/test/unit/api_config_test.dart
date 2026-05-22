import 'package:flutter_test/flutter_test.dart';
import 'package:yurostock_mobile/core/config/api_config.dart';

void main() {
  test('normalize adds index.php for MAMP paths', () {
    expect(
      ApiConfig.defaultImageFilename('jeans'),
      'defaultjeans.jpg',
    );
    expect(
      ApiConfig.defaultImageFilename('customcat'),
      'defaultcustomcat.jpg',
    );
  });

  test('stockBaseFromApiUrl strips api suffix', () {
    const api = 'http://127.0.0.1:8888/stock/api/v1/index.php';
    expect(ApiConfig.stockBaseFromApiUrl(api), 'http://127.0.0.1:8888/stock');
  });

  test('normalizeProductImageUrl uses default when empty', () {
    const api = 'http://127.0.0.1:8888/stock/api/v1/index.php';
    final url = ApiConfig.normalizeProductImageUrl(null, api, category: 'jeans');
    expect(url, contains('/include/uploads/defaultjeans.jpg'));
  });
}
