import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';

import 'support/test_harness.dart';

void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();

  group('POS checkout', () {
    testWidgets('jeans POS loads seeded Test Jean product', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      await IntegrationHarness.pumpPos(tester, container, category: 'jeans');
      expect(find.text('Test Jean'), findsWidgets);
      IntegrationHarness.assertNoFrameworkError(tester);
    });

    testWidgets('add to cart and open checkout sheet', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      await IntegrationHarness.pumpPos(tester, container);
      await IntegrationHarness.addTestJeanToCart(tester);
      await IntegrationHarness.openCheckoutSheet(tester);
      expect(find.text('Shop'), findsWidgets);
      expect(find.text('Delivery'), findsWidgets);
    });

    testWidgets('checkout bank dropdown does not crash with CBE', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      await IntegrationHarness.pumpPos(tester, container);
      await IntegrationHarness.addTestJeanToCart(tester);
      await IntegrationHarness.openCheckoutSheet(tester);

      if (find.byType(DropdownButtonFormField<String>).evaluate().isNotEmpty) {
        await tester.tap(find.byType(DropdownButtonFormField<String>).first);
        await tester.pumpAndSettle(const Duration(seconds: 3));
        if (find.text('CBE').evaluate().isNotEmpty) {
          await tester.tap(find.text('CBE').last);
          await tester.pumpAndSettle();
        }
      }
      IntegrationHarness.assertNoFrameworkError(tester);
    });

    testWidgets('delivery mode shows reason field and blocks empty confirm', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      await IntegrationHarness.pumpPos(tester, container);
      await IntegrationHarness.addTestJeanToCart(tester);
      await IntegrationHarness.openCheckoutSheet(tester);
      await IntegrationHarness.selectDeliveryMode(tester);

      await tester.tap(find.textContaining('Confirm delivery'));
      await tester.pumpAndSettle();
      expect(find.text('Enter who is delivering'), findsOneWidget);

      await IntegrationHarness.enterDeliveryReason(tester, 'Driver Test');
      IntegrationHarness.assertNoFrameworkError(tester);
    });

    testWidgets('complete shop sale shows success dialog', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      await IntegrationHarness.pumpPos(tester, container, category: 'shoes');
      await IntegrationHarness.addTestShoeToCart(tester);
      await IntegrationHarness.openCheckoutSheet(tester);
      await IntegrationHarness.confirmCheckout(tester);

      expect(
        find.text('Sale Complete').evaluate().isNotEmpty ||
            find.text('Delivery Created').evaluate().isNotEmpty,
        isTrue,
      );
      if (find.text('Done').evaluate().isNotEmpty) {
        await tester.tap(find.text('Done'));
        await tester.pumpAndSettle();
      }
      IntegrationHarness.assertNoFrameworkError(tester);
    });

    testWidgets('shoes POS loads Test Shoe', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      await IntegrationHarness.pumpPos(tester, container, category: 'shoes');
      expect(find.text('Test Shoe'), findsWidgets);
      IntegrationHarness.assertNoFrameworkError(tester);
    });

    testWidgets('cart quantity controls work', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      await IntegrationHarness.pumpPos(tester, container);
      await IntegrationHarness.addTestJeanToCart(tester);
      await IntegrationHarness.expandCart(tester);

      final addBtn = find.byIcon(Icons.add_circle_outline);
      if (addBtn.evaluate().isNotEmpty) {
        await tester.tap(addBtn.first);
        await tester.pumpAndSettle(const Duration(seconds: 2));
        expect(find.textContaining('qty 2'), findsWidgets);
      }
    });
  });
}
