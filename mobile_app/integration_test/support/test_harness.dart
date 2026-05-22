import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:yurostock_mobile/app.dart';
import 'package:yurostock_mobile/core/config/api_config.dart';
import 'package:yurostock_mobile/core/providers/app_providers.dart';
import 'package:yurostock_mobile/features/sales/pos_screen.dart';

import 'live_config.dart';

/// Shared helpers for live MAMP integration tests.
class IntegrationHarness {
  IntegrationHarness._();

  static Future<ProviderContainer> login() async {
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

  static Future<ProviderContainer> requireLogin() async {
    try {
      return await login();
    } catch (e) {
      fail(
        'API login failed (${LiveTestConfig.apiBase}): $e\n'
        'Start MAMP and run: php tests/fixtures/setup_test_shop.php',
      );
    }
  }

  static Future<void> pumpApp(WidgetTester tester, ProviderContainer container) async {
    await tester.pumpWidget(
      UncontrolledProviderScope(
        container: container,
        child: const YurostockApp(),
      ),
    );
    await tester.pumpAndSettle(const Duration(seconds: 12));
  }

  static Future<void> pumpPos(
    WidgetTester tester,
    ProviderContainer container, {
    String category = 'jeans',
  }) async {
    await tester.pumpWidget(
      UncontrolledProviderScope(
        container: container,
        child: MaterialApp(
          home: Scaffold(body: PosScreen(fixedCategory: category)),
        ),
      ),
    );
    await _waitForProducts(tester, category);
  }

  static Future<void> goToSalesPos(WidgetTester tester, ProviderContainer container) async {
    await pumpApp(tester, container);
    await tester.tap(find.byTooltip('Sales POS'));
    await tester.pumpAndSettle(const Duration(seconds: 10));
  }

  static Future<void> openSalesTab(WidgetTester tester, String label) async {
    await tester.tap(find.text(label));
    await tester.pumpAndSettle(const Duration(seconds: 10));
  }

  static Future<void> addTestJeanToCart(WidgetTester tester) async {
    await tapProductNamed(tester, 'Test Jean');
    await tester.tap(find.textContaining('M ('));
    await tester.pumpAndSettle(const Duration(seconds: 5));
    expect(find.text('Charge'), findsOneWidget);
  }

  static Future<void> addTestShoeToCart(WidgetTester tester) async {
    await tapProductNamed(tester, 'Test Shoe');
    await tester.tap(find.textContaining('40 ('));
    await tester.pumpAndSettle(const Duration(seconds: 5));
  }

  static Future<void> tapProductNamed(WidgetTester tester, String name) async {
    final finder = find.text(name);
    expect(finder, findsWidgets);
    await tester.tap(finder.first);
    await tester.pumpAndSettle(const Duration(seconds: 5));
    expect(find.text('Select size'), findsOneWidget);
  }

  static Future<void> openCheckoutSheet(WidgetTester tester) async {
    await tester.tap(find.text('Charge'));
    await tester.pumpAndSettle(const Duration(seconds: 5));
    expect(find.text('Checkout'), findsOneWidget);
    expect(tester.takeException(), isNull);
  }

  static Future<void> expandCart(WidgetTester tester) async {
    final show = find.textContaining('Show cart');
    if (show.evaluate().isNotEmpty) {
      await tester.tap(show.first);
      await tester.pumpAndSettle(const Duration(seconds: 2));
    }
  }

  static Future<void> selectDeliveryMode(WidgetTester tester) async {
    await tester.tap(find.text('Delivery'));
    await tester.pumpAndSettle();
    expect(find.text('Delivery person'), findsOneWidget);
  }

  /// Checkout sheet delivery reason field (not POS search).
  static Finder get checkoutDeliveryReasonField => find.byKey(const Key('checkout_delivery_reason'));

  static Future<void> enterDeliveryReason(WidgetTester tester, String reason) async {
    expect(checkoutDeliveryReasonField, findsOneWidget);
    await tester.enterText(checkoutDeliveryReasonField, reason);
    await tester.pumpAndSettle();
  }

  static Future<void> confirmCheckout(WidgetTester tester) async {
    final confirm = find.textContaining('Confirm');
    expect(confirm, findsWidgets);
    await tester.tap(confirm.last);
    await tester.pumpAndSettle(const Duration(seconds: 10));
  }

  static void assertNoFrameworkError(WidgetTester tester) {
    expect(tester.takeException(), isNull);
  }

  static Future<void> _waitForProducts(WidgetTester tester, String category) async {
    const attempts = 40;
    for (var i = 0; i < attempts; i++) {
      await tester.pump(const Duration(milliseconds: 500));
      if (find.textContaining('Loading products').evaluate().isEmpty &&
          (find.text('Test Jean').evaluate().isNotEmpty ||
              find.text('Test Shoe').evaluate().isNotEmpty ||
              find.text('No products').evaluate().isNotEmpty ||
              find.byIcon(Icons.error_outline).evaluate().isNotEmpty)) {
        return;
      }
    }
  }
}
