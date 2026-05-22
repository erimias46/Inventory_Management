class CartLine {
  CartLine({
    required this.type,
    required this.name,
    required this.size,
    required this.price,
    this.cash = 0,
    this.bank = 0,
    this.quantity = 1,
    this.imageUrl,
  });

  final String type;
  final String name;
  final String size;
  double price;
  double cash;
  double bank;
  int quantity;
  final String? imageUrl;

  double get lineTotal => price * quantity;

  Map<String, dynamic> toJson() => {
        'type': type,
        'name': name,
        'size': size,
        'price': price,
        'cash': cash > 0 ? cash : price,
        'bank': bank,
        'quantity': quantity,
      };

  CartLine copyWith({int? quantity, double? cash, double? bank}) => CartLine(
        type: type,
        name: name,
        size: size,
        price: price,
        cash: cash ?? this.cash,
        bank: bank ?? this.bank,
        quantity: quantity ?? this.quantity,
        imageUrl: imageUrl,
      );
}
