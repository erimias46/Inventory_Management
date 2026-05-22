import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../models/app_category.dart';
import '../models/user_model.dart';
import '../providers/app_providers.dart';

/// Default categories when API is unreachable or user not logged in yet.
List<AppCategory> fallbackCategories() => const [
      AppCategory(slug: 'jeans', label: 'Jeans', icon: 'fas fa-scroll', sortOrder: 1),
      AppCategory(slug: 'shoes', label: 'Shoes', icon: 'fas fa-shoe-prints', sortOrder: 2),
      AppCategory(slug: 'top', label: 'Top', icon: 'fas fa-tshirt', sortOrder: 3),
      AppCategory(slug: 'complete', label: 'Complete', icon: 'fas fa-box-open', sortOrder: 4),
      AppCategory(slug: 'accessory', label: 'Accessory', icon: 'fas fa-gem', sortOrder: 5),
      AppCategory(slug: 'wig', label: 'Wig', icon: 'fas fa-hat-wizard', sortOrder: 6),
      AppCategory(slug: 'cosmetics', label: 'Cosmetics', icon: 'fas fa-spa', sortOrder: 7),
    ];

List<Map<String, dynamic>> categoriesToChipMaps(List<AppCategory> cats) =>
    cats.map((c) => {'key': c.slug, 'label': c.label}).toList();

/// Loads shop categories from API; falls back to defaults on error or empty.
Future<List<AppCategory>> loadCategories(WidgetRef ref) async {
  try {
    final cats = await ref.read(categoriesProvider.future);
    if (cats.isNotEmpty) return cats;
  } catch (_) {}
  return fallbackCategories();
}

String categoryLabel(List<AppCategory> cats, String slug) {
  for (final c in cats) {
    if (c.slug == slug) return c.label;
  }
  if (slug.isEmpty) return slug;
  return '${slug[0].toUpperCase()}${slug.substring(1)}';
}

bool hasAnyModulePrefix(AppUser user, String prefix) {
  if (user.isMasterAdmin) return true;
  return user.modules.keys.any((k) => k.startsWith(prefix) && user.modules[k] == 1);
}
