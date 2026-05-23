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

  /// Max time for pumpAndSettle (avoids infinite spinners / chart animations).
  static const settleCap = Duration(seconds: 15);

  static Future<void> settle(WidgetTester tester, {Duration? cap}) async {
    await tester.pumpAndSettle(
      const Duration(milliseconds: 100),
      EnginePhase.sendSemanticsUpdate,
      cap ?? settleCap,
    );
  }

  /// Poll until [condition] is true (preferred over long pumpAndSettle on lists).
  static Future<void> waitUntil(
    WidgetTester tester,
    bool Function() condition, {
    Duration timeout = const Duration(seconds: 25),
  }) async {
    final end = DateTime.now().add(timeout);
    while (DateTime.now().isBefore(end)) {
      await tester.pump(const Duration(milliseconds: 300));
      if (condition()) return;
    }
    fail('Timed out after ${timeout.inSeconds}s waiting for UI condition');
  }

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
    await settle(tester, cap: const Duration(seconds: 20));
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
    await settle(tester);
  }

  static Future<void> openSalesTab(WidgetTester tester, String label) async {
    await tester.tap(find.text(label));
    await settle(tester);
  }

  static Future<void> addTestJeanToCart(WidgetTester tester) async {
    await tapProductNamed(tester, 'Test Jean');
    await tester.tap(find.textContaining('M ('));
    await settle(tester);
    expect(find.text('Charge'), findsOneWidget);
  }

  static Future<void> addTestShoeToCart(WidgetTester tester) async {
    await tapProductNamed(tester, 'Test Shoe');
    await tester.tap(find.textContaining('40 ('));
    await settle(tester);
  }

  static Future<void> tapProductNamed(WidgetTester tester, String name) async {
    final finder = find.text(name);
    expect(finder, findsWidgets);
    await tester.tap(finder.first);
    await settle(tester);
    expect(find.text('Select size'), findsOneWidget);
  }

  static Future<void> openCheckoutSheet(WidgetTester tester) async {
    await tester.tap(find.text('Charge'));
    await settle(tester);
    expect(find.text('Checkout'), findsOneWidget);
    expect(tester.takeException(), isNull);
  }

  static Future<void> expandCart(WidgetTester tester) async {
    final show = find.textContaining('Show cart');
    if (show.evaluate().isNotEmpty) {
      await tester.tap(show.first);
      await settle(tester, cap: const Duration(seconds: 3));
    }
  }

  static Future<void> selectDeliveryMode(WidgetTester tester) async {
    await tester.tap(find.text('Delivery'));
    await settle(tester, cap: const Duration(seconds: 3));
    expect(find.text('Delivery person'), findsOneWidget);
  }

  /// Checkout sheet delivery reason field (not POS search).
  static Finder get checkoutDeliveryReasonField => find.byKey(const Key('checkout_delivery_reason'));

  static Future<void> enterDeliveryReason(WidgetTester tester, String reason) async {
    expect(checkoutDeliveryReasonField, findsOneWidget);
    await tester.enterText(checkoutDeliveryReasonField, reason);
    await settle(tester, cap: const Duration(seconds: 3));
  }

  static Future<void> confirmCheckout(WidgetTester tester) async {
    final confirm = find.textContaining('Confirm');
    expect(confirm, findsWidgets);
    await tester.tap(confirm.last);
    await settle(tester);
  }

  static void assertNoFrameworkError(WidgetTester tester) {
    expect(tester.takeException(), isNull);
  }

  static Future<void> waitForScreenLoad(WidgetTester tester) async {
    await waitUntil(
      tester,
      () => find.byType(CircularProgressIndicator).evaluate().isEmpty,
    );
    await tester.pump(const Duration(milliseconds: 400));
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
