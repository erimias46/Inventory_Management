class AppUser {
  AppUser({
    required this.id,
    required this.userName,
    required this.privilege,
    required this.isMasterAdmin,
    required this.modules,
  });

  final int id;
  final String userName;
  final String privilege;
  final bool isMasterAdmin;
  final Map<String, int> modules;

  factory AppUser.fromJson(Map<String, dynamic> json) {
    final mods = json['modules'];
    final Map<String, int> modules = {};
    if (mods is Map) {
      mods.forEach((k, v) {
        modules[k.toString()] = (v == true || v == 1) ? 1 : 0;
      });
    }
    return AppUser(
      id: json['id'] as int? ?? 0,
      userName: json['user_name']?.toString() ?? '',
      privilege: json['privilege']?.toString() ?? 'user',
      isMasterAdmin: json['is_master_admin'] == true,
      modules: modules,
    );
  }

  bool hasModule(String key) {
    if (isMasterAdmin) return true;
    return modules[key] == 1;
  }
}
