import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../shared/pos_widgets.dart';

/// Search across categories or by multiple sizes (web: search_multi.php).
class MultiSearchScreen extends ConsumerStatefulWidget {
  const MultiSearchScreen({super.key, this.embedded = false});

  final bool embedded;

  @override
  ConsumerState<MultiSearchScreen> createState() => _MultiSearchScreenState();
}

class _MultiSearchScreenState extends ConsumerState<MultiSearchScreen> with SingleTickerProviderStateMixin {
  late TabController _tabs;
  final _queryCtrl = TextEditingController();
  List<Map<String, dynamic>> _types = [];
  String? _type;
  final List<TextEditingController> _sizeCtrls = [TextEditingController()];
  List<Map<String, dynamic>> _results = [];
  bool _loading = false;

  @override
  void initState() {
    super.initState();
    _tabs = TabController(length: 2, vsync: this);
    ref.read(salesRepositoryProvider).productTypes().then((t) {
      if (mounted) {
        setState(() {
          _types = t;
          if (t.isNotEmpty) _type = t.first['key'] as String;
        });
      }
    });
  }

  @override
  void dispose() {
    _tabs.dispose();
    _queryCtrl.dispose();
    for (final c in _sizeCtrls) {
      c.dispose();
    }
    super.dispose();
  }

  Future<void> _searchAll() async {
    setState(() => _loading = true);
    _results = await ref.read(salesRepositoryProvider).searchAllProducts(q: _queryCtrl.text.trim());
    setState(() => _loading = false);
  }

  Future<void> _searchMulti() async {
    if (_type == null) return;
    final sizes = _sizeCtrls.map((c) => c.text.trim()).where((s) => s.isNotEmpty).toList();
    setState(() => _loading = true);
    _results = await ref.read(salesRepositoryProvider).searchMulti(_type!, sizes);
    setState(() => _loading = false);
  }

  @override
  Widget build(BuildContext context) {
    final body = Column(
      children: [
        TabBar(
          controller: _tabs,
          tabs: const [
            Tab(text: 'All categories'),
            Tab(text: 'Multi-size'),
          ],
        ),
        Expanded(
          child: TabBarView(
            controller: _tabs,
            children: [
              _allTab(),
              _multiTab(),
            ],
          ),
        ),
      ],
    );

    if (widget.embedded) {
      return Column(
        children: [
          const PosHeader(title: 'Search', subtitle: 'All categories or multi-size'),
          Expanded(child: body),
        ],
      );
    }
    return Scaffold(appBar: AppBar(title: const Text('Search')), body: body);
  }

  Widget _allTab() {
    return Column(
      children: [
        Padding(
          padding: const EdgeInsets.all(12),
          child: Row(
            children: [
              Expanded(
                child: TextField(
                  controller: _queryCtrl,
                  decoration: const InputDecoration(hintText: 'Search all categories...', prefixIcon: Icon(Icons.search)),
                  onSubmitted: (_) => _searchAll(),
                ),
              ),
              const SizedBox(width: 8),
              FilledButton(onPressed: _loading ? null : _searchAll, child: const Text('Go')),
            ],
          ),
        ),
        Expanded(child: _resultList()),
      ],
    );
  }

  Widget _multiTab() {
    return Column(
      children: [
        Padding(
          padding: const EdgeInsets.all(12),
          child: Column(
            children: [
              if (_types.isNotEmpty)
                DropdownButtonFormField<String>(
                  initialValue: _type,
                  decoration: const InputDecoration(labelText: 'Category'),
                  items: _types
                      .map((t) => DropdownMenuItem(value: t['key'] as String, child: Text(t['label']?.toString() ?? t['key'])))
                      .toList(),
                  onChanged: (v) => setState(() => _type = v),
                ),
              ..._sizeCtrls.asMap().entries.map((e) {
                return Padding(
                  padding: const EdgeInsets.only(top: 8),
                  child: Row(
                    children: [
                      Expanded(
                        child: TextField(
                          controller: e.value,
                          decoration: InputDecoration(labelText: 'Size ${e.key + 1}'),
                        ),
                      ),
                      if (_sizeCtrls.length > 1)
                        IconButton(
                          icon: const Icon(Icons.close),
                          onPressed: () => setState(() {
                            e.value.dispose();
                            _sizeCtrls.removeAt(e.key);
                          }),
                        ),
                    ],
                  ),
                );
              }),
              TextButton.icon(
                onPressed: () => setState(() => _sizeCtrls.add(TextEditingController())),
                icon: const Icon(Icons.add),
                label: const Text('Add size'),
              ),
              const SizedBox(height: 8),
              FilledButton(onPressed: _loading ? null : _searchMulti, child: const Text('Find products with all sizes')),
            ],
          ),
        ),
        Expanded(child: _resultList()),
      ],
    );
  }

  Widget _resultList() {
    if (_loading) return const Center(child: CircularProgressIndicator());
    if (_results.isEmpty) return const EmptyState(icon: Icons.search_off, message: 'No matches');
    return ListView.builder(
      padding: const EdgeInsets.symmetric(horizontal: 12),
      itemCount: _results.length,
      itemBuilder: (_, i) {
        final r = _results[i];
        return Card(
          margin: const EdgeInsets.only(bottom: 8),
          child: ListTile(
            title: Text(r['name']?.toString() ?? '', style: const TextStyle(fontWeight: FontWeight.w700)),
            subtitle: Text(
              '${r['source'] ?? _type} · ${r['size_summary'] ?? r['size']} · qty ${r['quantity']}',
            ),
            trailing: r['price'] != null
                ? Text(currencyFmt.format((r['price'] as num).toDouble()), style: const TextStyle(color: AppColors.posGreen, fontWeight: FontWeight.w700))
                : null,
          ),
        );
      },
    );
  }
}
