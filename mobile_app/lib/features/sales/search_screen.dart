import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../../core/utils/category_utils.dart';
import '../shared/pos_widgets.dart';

class SearchScreen extends ConsumerStatefulWidget {
  const SearchScreen({super.key, this.embedded = false});

  final bool embedded;

  @override
  ConsumerState<SearchScreen> createState() => _SearchScreenState();
}

class _SearchScreenState extends ConsumerState<SearchScreen> {
  List<Map<String, dynamic>> _types = [];
  String? _type;
  final _queryCtrl = TextEditingController();
  final _sizeCtrl = TextEditingController();
  List<Map<String, dynamic>> _results = [];
  bool _loading = false;
  bool _searched = false;

  @override
  void initState() {
    super.initState();
    _loadTypes();
  }

  Future<void> _loadTypes() async {
    final cats = await loadCategories(ref);
    if (!mounted) return;
    setState(() {
      _types = categoriesToChipMaps(cats);
      if (_types.isNotEmpty) _type = _types.first['key'] as String;
    });
  }

  @override
  void dispose() {
    _queryCtrl.dispose();
    _sizeCtrl.dispose();
    super.dispose();
  }

  Future<void> _search() async {
    if (_type == null) return;
    setState(() {
      _loading = true;
      _searched = true;
    });
    _results = await ref.read(salesRepositoryProvider).searchProducts(
          _type!,
          size: _sizeCtrl.text.isEmpty ? null : _sizeCtrl.text,
          q: _queryCtrl.text.isEmpty ? null : _queryCtrl.text,
        );
    setState(() => _loading = false);
  }

  @override
  Widget build(BuildContext context) {
    final content = Column(
      crossAxisAlignment: CrossAxisAlignment.stretch,
      children: [
        if (widget.embedded) const PosHeader(title: 'Stock Search', subtitle: 'Find products across inventory'),
        Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            children: [
              if (_types.isNotEmpty)
                CategoryChipBar(
                  categories: _types,
                  selected: _type,
                  onSelected: (t) => setState(() => _type = t),
                ),
              const SizedBox(height: 12),
              TextField(
                controller: _queryCtrl,
                decoration: const InputDecoration(hintText: 'Product name', prefixIcon: Icon(Icons.inventory_2_outlined)),
                onSubmitted: (_) => _search(),
              ),
              const SizedBox(height: 8),
              TextField(
                controller: _sizeCtrl,
                decoration: const InputDecoration(hintText: 'Size (optional)', prefixIcon: Icon(Icons.straighten)),
                onSubmitted: (_) => _search(),
              ),
              const SizedBox(height: 12),
              FilledButton.icon(
                onPressed: _loading ? null : _search,
                icon: _loading ? const SizedBox(width: 18, height: 18, child: CircularProgressIndicator(strokeWidth: 2)) : const Icon(Icons.search),
                label: const Text('Search Stock'),
              ),
            ],
          ),
        ),
        Expanded(
          child: !_searched
              ? const EmptyState(icon: Icons.search, message: 'Search by name or size')
              : _results.isEmpty
                  ? const EmptyState(icon: Icons.search_off, message: 'No products found')
                  : ListView.builder(
                      padding: const EdgeInsets.symmetric(horizontal: 12),
                      itemCount: _results.length,
                      itemBuilder: (_, i) {
                        final r = _results[i];
                        final qty = (r['quantity'] as num?)?.toDouble() ?? 0;
                        return Card(
                          margin: const EdgeInsets.only(bottom: 8),
                          child: ListTile(
                            leading: CircleAvatar(
                              backgroundColor: qty < 3 ? AppColors.warning.withValues(alpha: 0.2) : AppColors.success.withValues(alpha: 0.2),
                              child: Text(
                                qty.toStringAsFixed(0),
                                style: TextStyle(
                                  fontSize: 12,
                                  fontWeight: FontWeight.w800,
                                  color: qty < 3 ? AppColors.warning : AppColors.success,
                                ),
                              ),
                            ),
                            title: Text(r['name']?.toString() ?? '', style: const TextStyle(fontWeight: FontWeight.w600)),
                            subtitle: Text('Size ${r['size']} · ${r['id']}'),
                            trailing: Text(
                              currencyFmt.format((r['price'] as num?)?.toDouble() ?? 0),
                              style: const TextStyle(fontWeight: FontWeight.w800, color: AppColors.posGreen),
                            ),
                          ),
                        );
                      },
                    ),
        ),
      ],
    );

    if (widget.embedded) return content;
    return Scaffold(appBar: AppBar(title: const Text('Search')), body: content);
  }
}
