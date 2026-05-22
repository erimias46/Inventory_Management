import 'package:flutter/material.dart';

class AppCategory {
  const AppCategory({
    required this.slug,
    required this.label,
    required this.icon,
    required this.sortOrder,
    this.defaultImage = '',
  });

  final String slug;
  final String label;
  final String icon;
  final int sortOrder;
  final String defaultImage;

  factory AppCategory.fromJson(Map<String, dynamic> json) => AppCategory(
        slug: json['slug']?.toString() ?? '',
        label: json['label']?.toString() ?? '',
        icon: json['icon']?.toString() ?? 'fas fa-box',
        sortOrder: json['sort_order'] as int? ?? 0,
        defaultImage: json['default_image']?.toString() ?? '',
      );

  IconData get materialIcon {
    const slugMap = {
      'jeans': Icons.checkroom,
      'shoes': Icons.shopping_bag,
      'top': Icons.style,
      'complete': Icons.layers,
      'accessory': Icons.watch,
      'wig': Icons.face_3,
      'cosmetics': Icons.spa,
    };
    if (slugMap.containsKey(slug)) return slugMap[slug]!;

    final fa = icon.toLowerCase();
    if (fa.contains('shoe') || fa.contains('boot')) return Icons.shopping_bag;
    if (fa.contains('shirt') || fa.contains('tshirt') || fa.contains('vest')) return Icons.checkroom;
    if (fa.contains('gem') || fa.contains('ring') || fa.contains('diamond')) return Icons.diamond;
    if (fa.contains('watch')) return Icons.watch;
    if (fa.contains('spa') || fa.contains('leaf')) return Icons.spa;
    if (fa.contains('face') || fa.contains('wig') || fa.contains('hat')) return Icons.face_3;
    if (fa.contains('phone') || fa.contains('mobile')) return Icons.smartphone;
    if (fa.contains('laptop') || fa.contains('computer')) return Icons.laptop;
    if (fa.contains('book')) return Icons.menu_book;
    if (fa.contains('food') || fa.contains('pizza')) return Icons.fastfood;
    if (fa.contains('bag') || fa.contains('purse')) return Icons.shopping_bag;
    if (fa.contains('box') || fa.contains('archive')) return Icons.inventory_2;
    if (fa.contains('tag') || fa.contains('label')) return Icons.label;
    return Icons.inventory_2;
  }

  Color get accentColor {
    const palette = [
      Color(0xFF3B82F6),
      Color(0xFF8B5CF6),
      Color(0xFFEC4899),
      Color(0xFF14B8A6),
      Color(0xFFF59E0B),
      Color(0xFF6366F1),
      Color(0xFF22C55E),
      Color(0xFFEF4444),
      Color(0xFF06B6D4),
      Color(0xFFF97316),
    ];
    final idx = (sortOrder - 1).clamp(0, palette.length - 1);
    return palette[idx];
  }
}
