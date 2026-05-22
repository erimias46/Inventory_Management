import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:yurostock_mobile/core/network/api_client.dart';
import 'package:yurostock_mobile/core/providers/app_providers.dart';
import 'package:yurostock_mobile/features/admin/admin_repository.dart';
import 'package:yurostock_mobile/features/admin/dashboard_screen.dart';

class _FakeAdminRepo extends AdminRepository {
  _FakeAdminRepo() : super(ApiClient(const FlutterSecureStorage()));

  @override
  Future<Map<String, dynamic>> dashboardOverview({String period = '30', int? year}) async {
    return {
      'period_label': 'Last 30 days',
      'kpis': {
        'profit': 500,
        'earnings': 1200,
        'quantity_sold': 42,
        'transactions': 10,
        'cash': 800,
        'bank': 400,
      },
      'today': {'earnings': 100, 'quantity': 5, 'transactions': 2, 'cash': 80, 'bank': 20},
      'activity': {'shop': 8, 'delivery': 1, 'exchange': 0, 'refund': 1},
      'by_category': [
        {'slug': 'jeans', 'label': 'Jeans', 'revenue': 600, 'quantity': 20, 'profit': 200},
      ],
      'banks': [{'name': 'CBE', 'total': 400}],
      'top_products': [{'name': 'Test Jean', 'quantity': 5, 'price': 100}],
      'stock': {'total_added': 15, 'recent': []},
      'monthly': List.generate(
        12,
        (i) => {
          'month': '2026-${(i + 1).toString().padLeft(2, '0')}',
          'label': 'Month',
          'quantity': i,
          'revenue': 0.0,
          'profit': 0.0,
          'avg_sale': 0.0,
          'avg_profit': 0.0,
        },
      ),
    };
  }

  @override
  Future<Map<String, dynamic>> dailySales({required int month, required int year}) async {
    return {'categories': [1, 2, 3], 'series': [1, 2, 0]};
  }
}

void main() {
  testWidgets('Dashboard shows KPI cards from mocked overview', (tester) async {
    await tester.pumpWidget(
      ProviderScope(
        overrides: [
          adminRepositoryProvider.overrideWith((ref) => _FakeAdminRepo()),
        ],
        child: const MaterialApp(
          home: Scaffold(body: DashboardScreen(embedded: true)),
        ),
      ),
    );
    await tester.pump();
    await tester.pumpAndSettle(const Duration(seconds: 2));

    expect(find.text('Dashboard'), findsOneWidget);
    expect(find.text('Profit'), findsOneWidget);
    expect(find.text('Earnings'), findsOneWidget);
  });
}
