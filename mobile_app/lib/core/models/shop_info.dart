class ShopInfo {
  const ShopInfo({required this.id, required this.name, required this.slug});

  final int id;
  final String name;
  final String slug;

  factory ShopInfo.fromJson(Map<String, dynamic> json) => ShopInfo(
        id: json['id'] as int? ?? 0,
        name: json['name']?.toString() ?? '',
        slug: json['slug']?.toString() ?? '',
      );
}
