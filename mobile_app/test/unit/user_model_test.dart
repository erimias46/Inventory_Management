import 'package:flutter_test/flutter_test.dart';
import 'package:yurostock_mobile/core/models/user_model.dart';

void main() {
  test('AppUser.fromJson with shop', () {
    final user = AppUser.fromJson(
      {
        'id': 1,
        'user_name': 'masteradmin',
        'privilege': 'admin',
        'is_master_admin': true,
        'modules': {'fullsale': 1, 'user': 0},
      },
      shop: {'slug': 'testshop', 'name': 'Test Shop'},
    );
    expect(user.userName, 'masteradmin');
    expect(user.isMasterAdmin, true);
    expect(user.shopSlug, 'testshop');
    expect(user.shopName, 'Test Shop');
    expect(user.hasModule('fullsale'), true);
    expect(user.hasModule('user'), true);
  });

  test('non-admin respects modules', () {
    final user = AppUser.fromJson({
      'id': 2,
      'user_name': 'testuser',
      'privilege': 'user',
      'is_master_admin': false,
      'modules': {'viewjeans': 1, 'user': 0},
    });
    expect(user.hasModule('viewjeans'), true);
    expect(user.hasModule('user'), false);
  });
}
