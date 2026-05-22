import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../core/models/app_category.dart';
import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../../core/utils/category_utils.dart';
import '../shared/pos_widgets.dart';

class CategoriesScreen extends ConsumerWidget {
  const CategoriesScreen({super.key, this.embedded = false});

  final bool embedded;

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final categoriesAsync = ref.watch(categoriesProvider);

    return categoriesAsync.when(
      loading: () => _buildShell(embedded, _LoadingGrid()),
      error: (e, st) => _buildShell(embedded, _FallbackGrid()),
      data: (cats) {
        final grid = cats.isEmpty ? _FallbackGrid() : _CategoryGrid(categories: cats);
        return _buildShell(embedded, grid);
      },
    );
  }

  Widget _buildShell(bool embedded, Widget grid) {
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

class _CategoryGrid extends StatelessWidget {
  const _CategoryGrid({required this.categories});
  final List<AppCategory> categories;

  @override
  Widget build(BuildContext context) {
    return GridView.builder(
      padding: const EdgeInsets.all(16),
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 2,
        mainAxisSpacing: 12,
        crossAxisSpacing: 12,
        childAspectRatio: 1.05,
      ),
      itemCount: categories.length,
      itemBuilder: (context, i) {
        final cat = categories[i];
        return _CategoryCard(category: cat);
      },
    );
  }
}

class _CategoryCard extends StatelessWidget {
  const _CategoryCard({required this.category});
  final AppCategory category;

  @override
  Widget build(BuildContext context) {
    final color = category.accentColor;
    return Material(
      color: AppColors.navyCard,
      borderRadius: BorderRadius.circular(16),
      child: InkWell(
        onTap: () => context.push('/admin/inventory/${category.slug}'),
        borderRadius: BorderRadius.circular(16),
        child: Padding(
          padding: const EdgeInsets.all(18),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: color.withValues(alpha: 0.2),
                  borderRadius: BorderRadius.circular(14),
                ),
                child: Icon(category.materialIcon, color: color, size: 28),
              ),
              const Spacer(),
              Text(category.label, style: const TextStyle(fontSize: 17, fontWeight: FontWeight.w800)),
              Text(
                'Manage stock',
                style: const TextStyle(color: AppColors.textMuted, fontSize: 12),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _LoadingGrid extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return GridView.builder(
      padding: const EdgeInsets.all(16),
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 2,
        mainAxisSpacing: 12,
        crossAxisSpacing: 12,
        childAspectRatio: 1.05,
      ),
      itemCount: 6,
      itemBuilder: (context, i) => Container(
        decoration: BoxDecoration(
          color: AppColors.navyCard,
          borderRadius: BorderRadius.circular(16),
        ),
        child: const Center(child: CircularProgressIndicator(strokeWidth: 2)),
      ),
    );
  }
}

class _FallbackGrid extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return _CategoryGrid(categories: fallbackCategories());
  }
}
