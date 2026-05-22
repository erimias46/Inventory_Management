// Default widget smoke test — see test/widget/ and test/unit/ for full coverage.
import 'package:flutter_test/flutter_test.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:yurostock_mobile/main.dart';

void main() {
  testWidgets('App boots', (tester) async {
    await tester.pumpWidget(const ProviderScope(child: BootstrapApp()));
    expect(find.byType(BootstrapApp), findsOneWidget);
  });
}
