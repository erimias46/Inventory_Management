import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:yurostock_mobile/core/router/app_router.dart';
import 'package:yurostock_mobile/features/sales/all_product_types_screen.dart';
import 'package:yurostock_mobile/features/admin/add_product_screen.dart';

import 'support/binding.dart';
import 'support/test_harness.dart';

void main() {
  configureIntegrationTestBinding();

  testWidgets('AllProductTypesScreen renders API rows', (tester) async {
    final container = await IntegrationHarness.requireLogin();
    addTearDown(container.dispose);

    await tester.pumpWidget(
      UncontrolledProviderScope(
        container: container,
        child: const MaterialApp(home: AllProductTypesScreen()),
      ),
    );
    await IntegrationHarness.waitUntil(
      tester,
      () => find.byType(CircularProgressIndicator).evaluate().isEmpty,
    );
    await tester.pump(const Duration(milliseconds: 500));

    IntegrationHarness.assertNoFrameworkError(tester);
    expect(
      find.text('No products in stock').evaluate().isNotEmpty ||
          find.byType(ExpansionTile).evaluate().isNotEmpty,
      isTrue,
    );
  });

  testWidgets('AddProductScreen jeans metadata loads', (tester) async {
    final container = await IntegrationHarness.requireLogin();
    addTearDown(container.dispose);

    await tester.pumpWidget(
      UncontrolledProviderScope(
        container: container,
        child: const MaterialApp(home: AddProductScreen(type: 'jeans')),
      ),
    );
    await IntegrationHarness.waitUntil(
      tester,
      () => find.byType(CircularProgressIndicator).evaluate().isEmpty,
    );

    IntegrationHarness.assertNoFrameworkError(tester);
    expect(find.text('Add Jeans'), findsOneWidget);
    expect(find.text('Product name'), findsOneWidget);
  });

  testWidgets('admin router reaches tools without exception', (tester) async {
    final container = await IntegrationHarness.requireLogin();
    addTearDown(container.dispose);

    final router = container.read(routerProvider);
    await tester.pumpWidget(
      UncontrolledProviderScope(
        container: container,
        child: MaterialApp.router(routerConfig: router),
      ),
    );
    await IntegrationHarness.settle(tester, cap: const Duration(seconds: 10));

    router.go('/admin/tools');
    await IntegrationHarness.settle(tester, cap: const Duration(seconds: 10));
    IntegrationHarness.assertNoFrameworkError(tester);
  });
}
