import 'package:flutter_test/flutter_test.dart';
import 'package:yurostock_mobile/core/utils/json_parse.dart';

void main() {
  test('parseJsonDouble handles string and num', () {
    expect(parseJsonDouble('12.5'), 12.5);
    expect(parseJsonDouble(3), 3.0);
    expect(parseJsonDouble(null), 0);
    expect(parseJsonDouble('bad', 9), 9);
  });

  test('parseJsonInt handles string id from PHP', () {
    expect(parseJsonInt('42'), 42);
    expect(parseJsonInt(7), 7);
  });

  test('uniqueStrings dedupes bank names', () {
    expect(uniqueStrings(['CBE', 'CBE', ' Awash ', 'Awash']), ['Awash', 'CBE']);
  });
}
