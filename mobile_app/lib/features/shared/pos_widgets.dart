import 'package:flutter/material.dart';
import 'package:intl/intl.dart';

import '../../core/theme/app_theme.dart';

final currencyFmt = NumberFormat.currency(symbol: '\$', decimalDigits: 0);

class PosHeader extends StatelessWidget {
  const PosHeader({
    super.key,
    required this.title,
    this.subtitle,
    this.trailing,
  });

  final String title;
  final String? subtitle;
  final Widget? trailing;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(16, 8, 16, 12),
      child: Row(
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title, style: const TextStyle(fontSize: 22, fontWeight: FontWeight.w800)),
                if (subtitle != null)
                  Text(subtitle!, style: const TextStyle(color: AppColors.textMuted, fontSize: 13)),
              ],
            ),
          ),
          if (trailing != null) trailing!,
        ],
      ),
    );
  }
}

class CategoryChipBar extends StatelessWidget {
  const CategoryChipBar({
    super.key,
    required this.categories,
    required this.selected,
    required this.onSelected,
  });

  final List<Map<String, dynamic>> categories;
  final String? selected;
  final ValueChanged<String> onSelected;

  static IconData iconFor(String key) => switch (key) {
        'jeans' => Icons.checkroom,
        'shoes' => Icons.shopping_bag_outlined,
        'top' => Icons.style_outlined,
        'complete' => Icons.layers_outlined,
        'accessory' => Icons.watch_outlined,
        'wig' => Icons.face_3_outlined,
        'cosmetics' => Icons.spa_outlined,
        _ => Icons.inventory_2_outlined,
      };

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: 44,
      child: ListView.separated(
        scrollDirection: Axis.horizontal,
        padding: const EdgeInsets.symmetric(horizontal: 16),
        itemCount: categories.length,
        separatorBuilder: (_, __) => const SizedBox(width: 8),
        itemBuilder: (_, i) {
          final key = categories[i]['key'] as String;
          final label = categories[i]['label'] as String;
          final isSel = key == selected;
          return FilterChip(
            selected: isSel,
            showCheckmark: false,
            avatar: Icon(iconFor(key), size: 18, color: isSel ? Colors.white : AppColors.textMuted),
            label: Text(label),
            labelStyle: TextStyle(
              fontWeight: FontWeight.w600,
              color: isSel ? Colors.white : AppColors.textMuted,
            ),
            selectedColor: AppColors.accent,
            backgroundColor: AppColors.navyCard,
            side: BorderSide(color: isSel ? AppColors.accent : const Color(0xFF334155)),
            onSelected: (_) => onSelected(key),
          );
        },
      ),
    );
  }
}

class ProductGridTile extends StatelessWidget {
  const ProductGridTile({
    super.key,
    required this.name,
    required this.subtitle,
    required this.price,
    required this.onTap,
    this.imageUrl,
    this.lowStock = false,
  });

  final String name;
  final String subtitle;
  final double? price;
  final VoidCallback onTap;
  final String? imageUrl;
  final bool lowStock;

  @override
  Widget build(BuildContext context) {
    return Material(
      color: AppColors.navyCard,
      borderRadius: BorderRadius.circular(14),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(14),
        child: Padding(
          padding: const EdgeInsets.all(12),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Expanded(
                child: Container(
                  width: double.infinity,
                  decoration: BoxDecoration(
                    color: const Color(0xFF0F172A),
                    borderRadius: BorderRadius.circular(10),
                  ),
                  child: imageUrl != null && imageUrl!.isNotEmpty
                      ? ClipRRect(
                          borderRadius: BorderRadius.circular(10),
                          child: Image.network(imageUrl!, fit: BoxFit.cover, errorBuilder: (_, __, ___) => _placeholder()),
                        )
                      : _placeholder(),
                ),
              ),
              const SizedBox(height: 8),
              Text(
                name,
                maxLines: 2,
                overflow: TextOverflow.ellipsis,
                style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 13),
              ),
              Text(subtitle, style: const TextStyle(color: AppColors.textMuted, fontSize: 11)),
              if (price != null) ...[
                const SizedBox(height: 4),
                Text(
                  currencyFmt.format(price),
                  style: TextStyle(
                    color: lowStock ? AppColors.warning : AppColors.posGreen,
                    fontWeight: FontWeight.w800,
                    fontSize: 14,
                  ),
                ),
              ],
            ],
          ),
        ),
      ),
    );
  }

  Widget _placeholder() => const Center(child: Icon(Icons.image_outlined, color: AppColors.textMuted, size: 28));
}

class CartSummaryBar extends StatelessWidget {
  const CartSummaryBar({
    super.key,
    required this.itemCount,
    required this.total,
    required this.onCheckout,
    this.loading = false,
  });

  final int itemCount;
  final double total;
  final VoidCallback onCheckout;
  final bool loading;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.fromLTRB(16, 12, 16, 12),
      decoration: BoxDecoration(
        color: AppColors.navyLight,
        border: Border(top: BorderSide(color: Colors.white.withValues(alpha: 0.08))),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.3),
            blurRadius: 12,
            offset: const Offset(0, -4),
          ),
        ],
      ),
      child: SafeArea(
        top: false,
        child: Row(
          children: [
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
              decoration: BoxDecoration(
                color: AppColors.accent.withValues(alpha: 0.2),
                borderRadius: BorderRadius.circular(10),
              ),
              child: Text(
                '$itemCount',
                style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 18, color: AppColors.accentBright),
              ),
            ),
            const SizedBox(width: 14),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisSize: MainAxisSize.min,
                children: [
                  const Text('Total', style: TextStyle(color: AppColors.textMuted, fontSize: 12)),
                  Text(
                    currencyFmt.format(total),
                    style: const TextStyle(fontSize: 22, fontWeight: FontWeight.w800),
                  ),
                ],
              ),
            ),
            FilledButton(
              onPressed: loading || itemCount == 0 ? null : onCheckout,
              style: FilledButton.styleFrom(
                backgroundColor: AppColors.posGreen,
                minimumSize: const Size(140, 52),
              ),
              child: loading
                  ? const SizedBox(width: 22, height: 22, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                  : const Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Icon(Icons.payments_outlined, size: 20),
                        SizedBox(width: 8),
                        Text('Charge'),
                      ],
                    ),
            ),
          ],
        ),
      ),
    );
  }
}

class EmptyState extends StatelessWidget {
  const EmptyState({super.key, required this.icon, required this.message});

  final IconData icon;
  final String message;

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(32),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(icon, size: 56, color: AppColors.textMuted.withValues(alpha: 0.5)),
            const SizedBox(height: 16),
            Text(message, textAlign: TextAlign.center, style: const TextStyle(color: AppColors.textMuted)),
          ],
        ),
      ),
    );
  }
}

class StatBadge extends StatelessWidget {
  const StatBadge({super.key, required this.label, required this.value, this.color = AppColors.accent});

  final String label;
  final String value;
  final Color color;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.15),
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: color.withValues(alpha: 0.3)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: TextStyle(color: color, fontSize: 11, fontWeight: FontWeight.w600)),
          Text(value, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w800)),
        ],
      ),
    );
  }
}
