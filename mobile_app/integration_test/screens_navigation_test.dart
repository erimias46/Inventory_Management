import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';
import 'package:yurostock_mobile/core/config/api_config.dart';
import 'package:yurostock_mobile/core/router/app_router.dart';
import 'package:yurostock_mobile/core/providers/app_providers.dart';
import 'package:yurostock_mobile/features/sales/all_product_types_screen.dart';
import 'package:yurostock_mobile/features/admin/add_product_screen.dart';

import 'support/live_config.dart';

/// Pumps key screens with live API data — catches JSON cast / layout crashes.
void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();

  Future<ProviderContainer> loggedInContainer() async {
    await ApiConfig.setBaseUrl(LiveTestConfig.apiBase);
    final container = ProviderContainer();
    final user = await container.read(authRepositoryProvider).login(
          LiveTestConfig.user,
          LiveTestConfig.pass,
          shopSlug: LiveTestConfig.shopSlug,
        );
    container.read(currentUserProvider.notifier).state = user;
    return container;
  }

  testWidgets('AllProductTypesScreen renders API rows', (tester) async {
    late ProviderContainer container;
    try {
      container = await loggedInContainer();
    } catch (e) {
      fail('Login failed: $e — run setup_test_shop.php and start MAMP');
    }
    addTearDown(container.dispose);

    await tester.pumpWidget(
      UncontrolledProviderScope(
        container: container,
        child: MaterialApp(home: const AllProductTypesScreen()),
      ),
    );
    await tester.pumpAndSettle(const Duration(seconds: 10));

    expect(tester.takeException(), isNull);
    // Either products or empty state — both valid
    expect(
      find.byType(CircularProgressIndicator).evaluate().isEmpty ||
          find.textContaining('Product').evaluate().isNotEmpty ||
          find.text('No products in stock').evaluate().isNotEmpty,
      isTrue,
    );
  });

  testWidgets('AddProductScreen jeans metadata loads', (tester) async {
    late ProviderContainer container;
    try {
      container = await loggedInContainer();
    } catch (e) {
      fail('Login failed: $e');
    }
    addTearDown(container.dispose);

    await tester.pumpWidget(
      UncontrolledProviderScope(
        container: container,
        child: const MaterialApp(home: AddProductScreen(type: 'jeans')),
      ),
    );
    await tester.pumpAndSettle(const Duration(seconds: 10));

    expect(tester.takeException(), isNull);
    expect(find.text('Add Jeans'), findsOneWidget);
    expect(find.text('Product name'), findsOneWidget);
  });

  testWidgets('admin router reaches tools without exception', (tester) async {
    late ProviderContainer container;
    try {
      container = await loggedInContainer();
    } catch (e) {
      fail('Login failed: $e');
    }
    addTearDown(container.dispose);

    final router = container.read(routerProvider);
    await tester.pumpWidget(
      UncontrolledProviderScope(
        container: container,
        child: MaterialApp.router(routerConfig: router),
      ),
    );
    await tester.pumpAndSettle(const Duration(seconds: 6));

    router.go('/admin/tools');
    await tester.pumpAndSettle(const Duration(seconds: 6));
    expect(tester.takeException(), isNull);
  });
}
