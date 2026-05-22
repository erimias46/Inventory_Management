import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import '../../core/theme/app_theme.dart';
import '../shared/pos_widgets.dart';

class CategoriesScreen extends StatelessWidget {
  const CategoriesScreen({super.key, this.embedded = false});

  final bool embedded;

  static const categories = [
    ('jeans', 'Jeans', Icons.checkroom, Color(0xFF3B82F6)),
    ('shoes', 'Shoes', Icons.shopping_bag, Color(0xFF8B5CF6)),
    ('top', 'Top', Icons.style, Color(0xFFEC4899)),
    ('complete', 'Complete', Icons.layers, Color(0xFF14B8A6)),
    ('accessory', 'Accessory', Icons.watch, Color(0xFFF59E0B)),
    ('wig', 'Wig', Icons.face_3, Color(0xFF6366F1)),
    ('cosmetics', 'Cosmetics', Icons.spa, Color(0xFF22C55E)),
  ];

  @override
  Widget build(BuildContext context) {
    final grid = GridView.builder(
      padding: const EdgeInsets.all(16),
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 2,
        mainAxisSpacing: 12,
        crossAxisSpacing: 12,
        childAspectRatio: 1.05,
      ),
      itemCount: categories.length,
      itemBuilder: (_, i) {
        final c = categories[i];
        return Material(
          color: AppColors.navyCard,
          borderRadius: BorderRadius.circular(16),
          child: InkWell(
            onTap: () => context.push('/admin/inventory/${c.$1}'),
            borderRadius: BorderRadius.circular(16),
            child: Padding(
              padding: const EdgeInsets.all(18),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Container(
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(
                      color: c.$4.withValues(alpha: 0.2),
                      borderRadius: BorderRadius.circular(14),
                    ),
                    child: Icon(c.$3, color: c.$4, size: 28),
                  ),
                  const Spacer(),
                  Text(c.$2, style: const TextStyle(fontSize: 17, fontWeight: FontWeight.w800)),
                  Text(
                    c.$1 == 'jeans' ? 'Jeans / price calculator' : 'Manage stock',
                    style: const TextStyle(color: AppColors.textMuted, fontSize: 12),
                  ),
                ],
              ),
            ),
          ),
        );
      },
    );

    if (embedded) {
      return Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          const PosHeader(title: 'Inventory', subtitle: 'Select a category'),
          Expanded(child: grid),
        ],
      );
    }
    return Scaffold(appBar: AppBar(title: const Text('Categories')), body: grid);
  }
}
