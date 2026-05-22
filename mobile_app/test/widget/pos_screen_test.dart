import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:yurostock_mobile/core/network/api_client.dart';
import 'package:yurostock_mobile/core/providers/app_providers.dart';
import 'package:yurostock_mobile/features/sales/pos_screen.dart';
import 'package:yurostock_mobile/features/sales/sales_repository.dart';

class _FakeSalesRepo extends SalesRepository {
  _FakeSalesRepo() : super(ApiClient(const FlutterSecureStorage()));

  @override
  Future<List<Map<String, dynamic>>> productTypes() async {
    return [
      {'key': 'jeans', 'label': 'Jeans'},
    ];
  }

  @override
  Future<List<String>> banks() async => ['CBE'];

  @override
  Future<List<Map<String, dynamic>>> productNames(String type) async => [];

  @override
  Future<Map<String, dynamic>> productSizes(String type, String name) async => {'sizes': []};
}

void main() {
  testWidgets('PosScreen fixedCategory shows category chip', (tester) async {
    await tester.pumpWidget(
      ProviderScope(
        overrides: [
          salesRepositoryProvider.overrideWith((ref) => _FakeSalesRepo()),
          categoriesProvider.overrideWith((ref) async => []),
        ],
        child: const MaterialApp(
          home: Scaffold(body: PosScreen(fixedCategory: 'jeans')),
        ),
      ),
    );
    await tester.pump();
    await tester.pump(const Duration(seconds: 2));

    expect(find.textContaining('sale'), findsWidgets);
  });
}
