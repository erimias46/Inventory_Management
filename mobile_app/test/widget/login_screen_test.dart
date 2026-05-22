import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:yurostock_mobile/core/models/shop_info.dart';
import 'package:yurostock_mobile/core/network/api_client.dart';
import 'package:yurostock_mobile/core/providers/app_providers.dart';
import 'package:yurostock_mobile/features/auth/auth_repository.dart';
import 'package:yurostock_mobile/features/auth/login_screen.dart';

class _FakeAuthRepo extends AuthRepository {
  _FakeAuthRepo() : super(ApiClient(const FlutterSecureStorage()));

  @override
  Future<List<ShopInfo>> fetchShops() async {
    return const [
      ShopInfo(id: 1, name: 'Shop A', slug: 'a'),
      ShopInfo(id: 2, name: 'Shop B', slug: 'b'),
    ];
  }
}

void main() {
  testWidgets('Login shows shop dropdown when multiple shops', (tester) async {
    await tester.pumpWidget(
      ProviderScope(
        overrides: [
          authRepositoryProvider.overrideWith((ref) => _FakeAuthRepo()),
        ],
        child: const MaterialApp(home: LoginScreen()),
      ),
    );
    await tester.pump();
    await tester.pump(const Duration(seconds: 1));

    expect(find.text('Select shop'), findsOneWidget);
    expect(find.text('Sign In'), findsOneWidget);
  });
}
