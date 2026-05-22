import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:yurostock_mobile/core/network/api_client.dart';
import 'package:yurostock_mobile/core/providers/app_providers.dart';
import 'package:yurostock_mobile/features/sales/all_sales_screen.dart';
import 'package:yurostock_mobile/features/sales/sales_repository.dart';

class _FakeSalesList extends SalesRepository {
  _FakeSalesList() : super(ApiClient(const FlutterSecureStorage()));

  @override
  Future<List<Map<String, dynamic>>> listSales({int page = 1}) async => [
        {
          'source': 'jeans',
          'sales_id': 1,
          'product_name': 'Test Jean',
          'price': '100',
          'size': 'M',
          'sales_date': '2026-05-22 10:00:00',
          'method': 'shop',
        },
      ];
}

void main() {
  testWidgets('AllSalesScreen renders string price from API', (tester) async {
    await tester.pumpWidget(
      ProviderScope(
        overrides: [salesRepositoryProvider.overrideWith((ref) => _FakeSalesList())],
        child: const MaterialApp(home: AllSalesScreen()),
      ),
    );
    await tester.pumpAndSettle();

    expect(find.textContaining('Test Jean'), findsWidgets);
    expect(tester.takeException(), isNull);
  });
}
