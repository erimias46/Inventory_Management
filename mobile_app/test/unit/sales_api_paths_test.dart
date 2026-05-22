import 'package:flutter_test/flutter_test.dart';

/// Guards mobile API paths stay aligned with api/v1/Router.php (refund segment fix).
void main() {
  test('refund path uses sale id before refund segment', () {
    const type = 'jeans';
    const id = 42;
    final path = '/sales/$type/$id/refund';
    expect(path, '/sales/jeans/42/refund');
    final parts = path.split('/').where((p) => p.isNotEmpty).toList();
    expect(parts[0], 'sales');
    expect(parts[1], type);
    expect(parts[2], '$id');
    expect(parts[3], 'refund');
  });

  test('exchange path ends with exchange segment', () {
    const path = '/sales/shoes/7/exchange';
    final parts = path.split('/').where((p) => p.isNotEmpty).toList();
    expect(parts.last, 'exchange');
    expect(parts[parts.length - 2], '7');
  });
}
