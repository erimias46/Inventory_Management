// PHP/MySQL JSON often returns numbers as strings — safe parsing for API maps.

int parseJsonInt(dynamic value, [int fallback = 0]) {
  if (value == null) return fallback;
  if (value is int) return value;
  if (value is num) return value.toInt();
  if (value is String) return int.tryParse(value.trim()) ?? fallback;
  return fallback;
}

double parseJsonDouble(dynamic value, [double fallback = 0]) {
  if (value == null) return fallback;
  if (value is double) return value;
  if (value is int) return value.toDouble();
  if (value is num) return value.toDouble();
  if (value is String) return double.tryParse(value.trim()) ?? fallback;
  return fallback;
}

/// Unique non-empty strings (e.g. bank names from API).
List<String> uniqueStrings(Iterable<dynamic> values) {
  final seen = <String>{};
  final out = <String>[];
  for (final v in values) {
    final s = v?.toString().trim() ?? '';
    if (s.isNotEmpty && seen.add(s)) out.add(s);
  }
  out.sort();
  return out;
}
