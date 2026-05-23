import 'package:flutter_test/flutter_test.dart';
import 'support/binding.dart';
import 'support/test_harness.dart';

/// UI + API: login → admin dashboard → sales POS.
void main() {
  configureIntegrationTestBinding();

  testWidgets('login, dashboard loads, navigate to POS', (tester) async {
    final container = await IntegrationHarness.requireLogin();
    addTearDown(container.dispose);

    await IntegrationHarness.goToSalesPos(tester, container);
    expect(find.text('POS'), findsWidgets);
    IntegrationHarness.assertNoFrameworkError(tester);
  });
}
