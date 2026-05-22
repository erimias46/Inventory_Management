import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:yurostock_mobile/core/models/app_category.dart';

void main() {
  test('AppCategory.fromJson parses fields', () {
    final cat = AppCategory.fromJson({
      'slug': 'jeans',
      'label': 'Jeans',
      'icon': 'fas fa-scroll',
      'sort_order': 2,
      'default_image': 'defaultjeans.jpg',
    });
    expect(cat.slug, 'jeans');
    expect(cat.label, 'Jeans');
    expect(cat.sortOrder, 2);
    expect(cat.materialIcon, Icons.checkroom);
  });

  test('unknown slug uses icon heuristics', () {
    const cat = AppCategory(slug: 'phones', label: 'Phones', icon: 'fas fa-mobile', sortOrder: 3);
    expect(cat.materialIcon, Icons.smartphone);
  });
}
