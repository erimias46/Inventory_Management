import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:yurostock_mobile/core/network/api_client.dart';
import 'package:yurostock_mobile/core/providers/app_providers.dart';
import 'package:yurostock_mobile/features/admin/ops_repository.dart';
import 'package:yurostock_mobile/features/sales/all_product_types_screen.dart';

class _FakeOps extends OpsRepository {
  _FakeOps() : super(ApiClient(const FlutterSecureStorage()));

  @override
  Future<List<Map<String, dynamic>>> allProductTypes() async => [
        {
          'product_name': 'Test Jean',
          'category': 'jeans',
          'sizes': 'M (10)',
          'price': '100.50', // PHP JSON string — must not crash
          'total_quantity_now': '10',
          'total_received': '20',
          'total_sold': '5',
        },
      ];
}

void main() {
  testWidgets('AllProductTypesScreen handles string numeric fields', (tester) async {
    await tester.pumpWidget(
      ProviderScope(
        overrides: [opsRepositoryProvider.overrideWith((ref) => _FakeOps())],
        child: const MaterialApp(home: AllProductTypesScreen()),
      ),
    );
    await tester.pump();
    await tester.pumpAndSettle();

    expect(find.text('Test Jean'), findsOneWidget);
    expect(tester.takeException(), isNull);
  });
}
