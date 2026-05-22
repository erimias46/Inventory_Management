import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../core/models/shop_info.dart';
import '../../core/providers/app_providers.dart';
import '../../core/theme/app_theme.dart';
import '../shared/connection_banner.dart';

class LoginScreen extends ConsumerStatefulWidget {
  const LoginScreen({super.key});

  @override
  ConsumerState<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends ConsumerState<LoginScreen> {
  final _userCtrl = TextEditingController();
  final _passCtrl = TextEditingController();
  String? _error;
  bool _loading = false;
  bool _obscure = true;
  ShopInfo? _selectedShop;

  Future<void> _login() async {
    setState(() {
      _loading = true;
      _error = null;
    });
    try {
      final user = await ref.read(authRepositoryProvider).login(
            _userCtrl.text.trim(),
            _passCtrl.text,
            shopSlug: _selectedShop?.slug,
          );
      ref.read(currentUserProvider.notifier).state = user;
      ref.invalidate(connectionStatusProvider);
      ref.invalidate(categoriesProvider);
      if (!mounted) return;
      context.go(user.isMasterAdmin ? '/admin' : '/sales');
    } catch (e) {
      setState(() => _error = e.toString().replaceAll('ApiException: ', ''));
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final shopsAsync = ref.watch(shopsProvider);

    return Scaffold(
      body: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
            colors: [AppColors.navy, Color(0xFF1A0E40), AppColors.navy],
          ),
        ),
        child: SafeArea(
          child: Center(
            child: SingleChildScrollView(
              padding: const EdgeInsets.all(24),
              child: ConstrainedBox(
                constraints: const BoxConstraints(maxWidth: 400),
                child: Column(
                  children: [
                    Container(
                      padding: const EdgeInsets.all(22),
                      decoration: BoxDecoration(
                        gradient: const LinearGradient(colors: [AppColors.accent, Color(0xFF8B5CF6)]),
                        borderRadius: BorderRadius.circular(22),
                        boxShadow: [
                          BoxShadow(color: AppColors.accent.withValues(alpha: 0.4), blurRadius: 24, offset: const Offset(0, 8)),
                        ],
                      ),
                      child: const Icon(Icons.storefront_rounded, size: 52, color: Colors.white),
                    ),
                    const SizedBox(height: 28),
                    const Text('Yurostock', style: TextStyle(fontSize: 32, fontWeight: FontWeight.w800, letterSpacing: -0.5)),
                    const SizedBox(height: 6),
                    const Text('Inventory & Point of Sale', style: TextStyle(color: AppColors.textMuted)),
                    const SizedBox(height: 36),

                    // Shop selector
                    shopsAsync.when(
                      loading: () => _ShopSelectorSkeleton(),
                      error: (e, st) => const SizedBox.shrink(),
                      data: (shops) {
                        if (shops.isEmpty) return const SizedBox.shrink();
                        if (shops.length == 1) {
                          WidgetsBinding.instance.addPostFrameCallback((_) {
                            if (_selectedShop == null) {
                              setState(() => _selectedShop = shops.first);
                            }
                          });
                          return _ShopBadge(shop: shops.first);
                        }
                        return _ShopDropdown(
                          shops: shops,
                          selected: _selectedShop,
                          onChanged: (s) => setState(() => _selectedShop = s),
                        );
                      },
                    ),
                    const SizedBox(height: 16),

                    TextField(
                      controller: _userCtrl,
                      decoration: const InputDecoration(labelText: 'Username', prefixIcon: Icon(Icons.person_outline)),
                    ),
                    const SizedBox(height: 14),
                    TextField(
                      controller: _passCtrl,
                      obscureText: _obscure,
                      decoration: InputDecoration(
                        labelText: 'Password',
                        prefixIcon: const Icon(Icons.lock_outline),
                        suffixIcon: IconButton(
                          icon: Icon(_obscure ? Icons.visibility_outlined : Icons.visibility_off_outlined),
                          onPressed: () => setState(() => _obscure = !_obscure),
                        ),
                      ),
                      onSubmitted: (_) => _login(),
                    ),
                    if (_error != null) ...[
                      const SizedBox(height: 14),
                      Container(
                        width: double.infinity,
                        padding: const EdgeInsets.all(12),
                        decoration: BoxDecoration(
                          color: AppColors.danger.withValues(alpha: 0.15),
                          borderRadius: BorderRadius.circular(12),
                          border: Border.all(color: AppColors.danger.withValues(alpha: 0.4)),
                        ),
                        child: Text(_error!, style: const TextStyle(color: Color(0xFFFCA5A5), fontSize: 13)),
                      ),
                    ],
                    const SizedBox(height: 24),
                    SizedBox(
                      width: double.infinity,
                      height: 54,
                      child: FilledButton(
                        onPressed: _loading ? null : _login,
                        child: _loading
                            ? const SizedBox(width: 24, height: 24, child: CircularProgressIndicator(strokeWidth: 2))
                            : const Text('Sign In'),
                      ),
                    ),
                    TextButton(
                      onPressed: () => context.push('/settings'),
                      child: const Text('API Settings'),
                    ),
                  ],
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }
}

class _ShopBadge extends StatelessWidget {
  const _ShopBadge({required this.shop});
  final ShopInfo shop;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
      decoration: BoxDecoration(
        color: AppColors.accent.withValues(alpha: 0.12),
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: AppColors.accent.withValues(alpha: 0.3)),
      ),
      child: Row(
        children: [
          const Icon(Icons.storefront_rounded, size: 18, color: AppColors.accent),
          const SizedBox(width: 10),
          Expanded(
            child: Text(
              shop.name,
              style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 14),
            ),
          ),
        ],
      ),
    );
  }
}

class _ShopDropdown extends StatelessWidget {
  const _ShopDropdown({required this.shops, required this.selected, required this.onChanged});
  final List<ShopInfo> shops;
  final ShopInfo? selected;
  final ValueChanged<ShopInfo?> onChanged;

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        color: const Color(0xFF1E1B4B).withValues(alpha: 0.5),
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: Colors.white.withValues(alpha: 0.12)),
      ),
      child: DropdownButtonHideUnderline(
        child: ButtonTheme(
          alignedDropdown: true,
          child: DropdownButton<ShopInfo>(
            value: selected,
            hint: const Padding(
              padding: EdgeInsets.only(left: 4),
              child: Text('Select shop', style: TextStyle(color: AppColors.textMuted)),
            ),
            isExpanded: true,
            dropdownColor: const Color(0xFF1E1B4B),
            borderRadius: BorderRadius.circular(14),
            padding: const EdgeInsets.symmetric(horizontal: 12),
            icon: const Icon(Icons.expand_more, color: AppColors.textMuted),
            items: shops.map((s) => DropdownMenuItem(
              value: s,
              child: Row(
                children: [
                  const Icon(Icons.storefront_rounded, size: 16, color: AppColors.accent),
                  const SizedBox(width: 10),
                  Text(s.name, style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w600)),
                ],
              ),
            )).toList(),
            onChanged: onChanged,
          ),
        ),
      ),
    );
  }
}

class _ShopSelectorSkeleton extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      height: 48,
      decoration: BoxDecoration(
        color: Colors.white.withValues(alpha: 0.05),
        borderRadius: BorderRadius.circular(14),
      ),
    );
  }
}
