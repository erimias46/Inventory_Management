import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../core/providers/app_providers.dart';
import '../../core/models/user_model.dart';
import '../../core/theme/app_theme.dart';
import '../shared/pos_widgets.dart';

class InventoryListScreen extends ConsumerStatefulWidget {
  const InventoryListScreen({super.key, required this.type});

  final String type;

  @override
  ConsumerState<InventoryListScreen> createState() => _InventoryListScreenState();
}

class _InventoryListScreenState extends ConsumerState<InventoryListScreen> {
  List<Map<String, dynamic>> _items = [];
  final _searchCtrl = TextEditingController();
  bool _loading = true;

  String get _title => '${widget.type[0].toUpperCase()}${widget.type.substring(1)}';

  @override
  void initState() {
    super.initState();
    _load();
  }

  @override
  void dispose() {
    _searchCtrl.dispose();
    super.dispose();
  }

  Future<void> _load() async {
    setState(() => _loading = true);
    _items = await ref.read(adminRepositoryProvider).inventory(
          widget.type,
          q: _searchCtrl.text.isEmpty ? null : _searchCtrl.text,
        );
    setState(() => _loading = false);
  }

  bool _canAdd(AppUser? user) {
    if (user == null) return false;
    if (user.isMasterAdmin) return true;
    final key = widget.type == 'jeans' ? 'addjeans' : 'add${widget.type}';
    return user.hasModule(key);
  }

  bool _canDelete(AppUser? user) {
    if (user == null) return false;
    if (user.isMasterAdmin) return true;
    final key = widget.type == 'jeans' ? 'deletejeans' : 'delete${widget.type}';
    return user.hasModule(key);
  }

  Future<void> _deleteItem(Map<String, dynamic> item) async {
    final ok = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        backgroundColor: AppColors.navyCard,
        title: const Text('Delete product?'),
        content: Text('Remove ${item['name']} (${item['size']}) from inventory?'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx, false), child: const Text('Cancel')),
          FilledButton(
            style: FilledButton.styleFrom(backgroundColor: AppColors.danger),
            onPressed: () => Navigator.pop(ctx, true),
            child: const Text('Delete'),
          ),
        ],
      ),
    );
    if (ok == true) {
      await ref.read(adminRepositoryProvider).deleteInventory(widget.type, item['id'] as int);
      _load();
    }
  }

  Future<void> _showItemActions(Map<String, dynamic> item, bool canDel) async {
    final action = await showModalBottomSheet<String>(
      context: context,
      backgroundColor: AppColors.navyLight,
      builder: (ctx) => SafeArea(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            ListTile(
              leading: const Icon(Icons.edit),
              title: const Text('Quick edit qty/price'),
              onTap: () => Navigator.pop(ctx, 'quick'),
            ),
            ListTile(
              leading: const Icon(Icons.price_change),
              title: const Text('Edit price (full)'),
              onTap: () => Navigator.pop(ctx, 'price'),
            ),
            if (canDel)
              ListTile(
                leading: const Icon(Icons.delete_outline, color: AppColors.danger),
                title: const Text('Delete', style: TextStyle(color: AppColors.danger)),
                onTap: () => Navigator.pop(ctx, 'delete'),
              ),
          ],
        ),
      ),
    );
    if (action == 'quick') {
      await _editItem(item);
    } else if (action == 'price') {
      final ok = await context.push<bool>('/admin/inventory/${widget.type}/edit/${item['id']}');
      if (ok == true) _load();
    } else if (action == 'delete') {
      await _deleteItem(item);
    }
  }

  Future<void> _editItem(Map<String, dynamic> item) async {
    final priceCtrl = TextEditingController(text: item['price']?.toString() ?? '');
    final qtyCtrl = TextEditingController(text: item['quantity']?.toString() ?? '');
    final saved = await showModalBottomSheet<bool>(
      context: context,
      isScrollControlled: true,
      backgroundColor: AppColors.navyLight,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      builder: (ctx) => Padding(
        padding: EdgeInsets.only(bottom: MediaQuery.of(ctx).viewInsets.bottom, left: 24, right: 24, top: 24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Text(item['name']?.toString() ?? '', style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w800)),
            Text('Size ${item['size']}', style: const TextStyle(color: AppColors.textMuted)),
            const SizedBox(height: 16),
            TextField(controller: priceCtrl, keyboardType: TextInputType.number, decoration: const InputDecoration(labelText: 'Price')),
            const SizedBox(height: 12),
            TextField(controller: qtyCtrl, keyboardType: TextInputType.number, decoration: const InputDecoration(labelText: 'Quantity')),
            const SizedBox(height: 20),
            FilledButton(
              onPressed: () => Navigator.pop(ctx, true),
              child: const Text('Save Changes'),
            ),
            const SizedBox(height: 16),
          ],
        ),
      ),
    );
    if (saved == true) {
      await ref.read(adminRepositoryProvider).updateInventory(
            widget.type,
            item['id'] as int,
            price: double.tryParse(priceCtrl.text),
            quantity: double.tryParse(qtyCtrl.text),
          );
      _load();
    }
  }

  @override
  Widget build(BuildContext context) {
    final user = ref.watch(currentUserProvider);
    final canAdd = _canAdd(user);

    return Scaffold(
      appBar: AppBar(title: Text('$_title Inventory')),
      floatingActionButton: canAdd
          ? FloatingActionButton.extended(
              onPressed: () async {
                final added = await context.push<bool>('/admin/inventory/${widget.type}/add');
                if (added == true) _load();
              },
              icon: const Icon(Icons.add),
              label: const Text('Add'),
            )
          : null,
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(12),
            child: Row(
              children: [
                Expanded(
                  child: TextField(
                    controller: _searchCtrl,
                    decoration: const InputDecoration(
                      hintText: 'Search name or size...',
                      prefixIcon: Icon(Icons.search),
                    ),
                    onSubmitted: (_) => _load(),
                  ),
                ),
                const SizedBox(width: 8),
                IconButton.filled(onPressed: _load, icon: const Icon(Icons.refresh)),
              ],
            ),
          ),
          Expanded(
            child: _loading
                ? const Center(child: CircularProgressIndicator())
                : _items.isEmpty
                    ? EmptyState(icon: Icons.inventory_2_outlined, message: 'No products in $_title')
                    : ListView.builder(
                        padding: const EdgeInsets.symmetric(horizontal: 12),
                        itemCount: _items.length,
                        itemBuilder: (_, i) {
                          final item = _items[i];
                          final qty = (item['quantity'] as num?)?.toDouble() ?? 0;
                          final canDel = _canDelete(user);
                          return Card(
                            margin: const EdgeInsets.only(bottom: 8),
                            child: ListTile(
                              onTap: () => _showItemActions(item, canDel),
                              onLongPress: canDel ? () => _deleteItem(item) : null,
                              title: Text(item['name']?.toString() ?? '', style: const TextStyle(fontWeight: FontWeight.w600)),
                              subtitle: Text('Size ${item['size']}${canDel ? ' · long-press to delete' : ''}'),
                              trailing: Column(
                                mainAxisAlignment: MainAxisAlignment.center,
                                crossAxisAlignment: CrossAxisAlignment.end,
                                children: [
                                  Text(
                                    currencyFmt.format((item['price'] as num?)?.toDouble() ?? 0),
                                    style: const TextStyle(fontWeight: FontWeight.w700, color: AppColors.posGreen),
                                  ),
                                  Container(
                                    margin: const EdgeInsets.only(top: 4),
                                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                                    decoration: BoxDecoration(
                                      color: (qty < 3 ? AppColors.warning : AppColors.success).withValues(alpha: 0.2),
                                      borderRadius: BorderRadius.circular(8),
                                    ),
                                    child: Text(
                                      'Qty ${qty.toStringAsFixed(0)}',
                                      style: TextStyle(
                                        fontSize: 11,
                                        fontWeight: FontWeight.w700,
                                        color: qty < 3 ? AppColors.warning : AppColors.success,
                                      ),
                                    ),
                                  ),
                                ],
                              ),
                            ),
                          );
                        },
                      ),
          ),
        ],
      ),
    );
  }
}
