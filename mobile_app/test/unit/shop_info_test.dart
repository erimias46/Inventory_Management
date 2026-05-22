import 'package:flutter_test/flutter_test.dart';
import 'package:yurostock_mobile/core/models/shop_info.dart';

void main() {
  test('ShopInfo.fromJson', () {
    final shop = ShopInfo.fromJson({'id': 3, 'name': 'Test Shop', 'slug': 'testshop'});
    expect(shop.id, 3);
    expect(shop.name, 'Test Shop');
    expect(shop.slug, 'testshop');
  });
}
