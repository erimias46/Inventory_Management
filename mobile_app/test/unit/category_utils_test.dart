import 'package:flutter_test/flutter_test.dart';
import 'package:yurostock_mobile/core/models/app_category.dart';
import 'package:yurostock_mobile/core/models/user_model.dart';
import 'package:yurostock_mobile/core/utils/category_utils.dart';

void main() {
  test('categoriesToChipMaps', () {
    const cats = [AppCategory(slug: 'jeans', label: 'Jeans', icon: '', sortOrder: 1)];
    final maps = categoriesToChipMaps(cats);
    expect(maps, [{'key': 'jeans', 'label': 'Jeans'}]);
  });

  test('categoryLabel finds label or capitalizes slug', () {
    const cats = [AppCategory(slug: 'jeans', label: 'Jeans', icon: '', sortOrder: 1)];
    expect(categoryLabel(cats, 'jeans'), 'Jeans');
    expect(categoryLabel(cats, 'wig'), 'Wig');
  });

  test('hasAnyModulePrefix', () {
    final admin = AppUser(
      id: 1,
      userName: 'masteradmin',
      privilege: 'admin',
      isMasterAdmin: true,
      modules: {},
    );
    expect(hasAnyModulePrefix(admin, 'refundsale'), true);

    final limited = AppUser(
      id: 2,
      userName: 'u',
      privilege: 'user',
      isMasterAdmin: false,
      modules: {'refundsalejeans': 1},
    );
    expect(hasAnyModulePrefix(limited, 'refundsale'), true);
    expect(hasAnyModulePrefix(limited, 'exchangesale'), false);
  });

  test('fallbackCategories has seven defaults', () {
    expect(fallbackCategories().length, 7);
  });
}
