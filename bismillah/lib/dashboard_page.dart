import 'dart:convert';
import 'dart:typed_data';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class DashboardPage extends StatefulWidget {
  const DashboardPage({super.key});

  @override
  _DashboardPageState createState() => _DashboardPageState();
}

class _DashboardPageState extends State<DashboardPage> {
  int _currentIndex = 0;

  final List<Widget> _pages = [
    const HomeScreen(),
    const WorkoutScreen(),
    const ProgressScreen(),
    const AccountScreen(),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'Wed, 22 Nov 2023',
          style: TextStyle(color: Colors.black),
        ),
        backgroundColor: Colors.white,
        elevation: 0,
        actions: [
          IconButton(
            icon: const Icon(Icons.notifications_none, color: Colors.black),
            onPressed: () {
              // Aksi untuk tombol notifikasi
            },
          ),
        ],
      ),
      body: _pages[_currentIndex],
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _currentIndex,
        onTap: (index) {
          setState(() {
            _currentIndex = index;
          });
        },
        selectedItemColor: Colors.green,
        unselectedItemColor: Colors.grey,
        backgroundColor: Colors.white,
        items: const [
          BottomNavigationBarItem(
            icon: Icon(Icons.home),
            label: 'Home',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.fitness_center),
            label: 'Workout',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.bar_chart),
            label: 'Progress',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.person),
            label: 'Account',
          ),
        ],
      ),
    );
  }
}

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  _HomeScreenState createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  // Fungsi untuk mengambil data dari API
  Future<List<dynamic>> fetchLatihanData() async {
    final response =
        await http.get(Uri.parse('http://192.168.56.1:8000/api/latihan-data'));
    if (response.statusCode == 200) {
      return json.decode(response.body); // Mengembalikan daftar data JSON
    } else {
      throw Exception('Failed to load data');
    }
  }
 

 @override
Widget build(BuildContext context) {
  return FutureBuilder<List<dynamic>>(
    future: fetchLatihanData(),
    builder: (context, snapshot) {
      if (snapshot.connectionState == ConnectionState.waiting) {
        return const Center(child: CircularProgressIndicator());
      } else if (snapshot.hasError) {
        return Center(child: Text('Error: ${snapshot.error}'));
      } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
        return const Center(child: Text('No data available'));
      } else {
        final data = snapshot.data!;
        return Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              
              const SizedBox(height: 8),
             Wrap(
                  spacing: 16.0, // Jarak antar elemen horizontal
                  runSpacing: 8.0, // Jarak antar elemen vertikal
                  children: List.generate(7, (index) {
                    final days = ['M', 'T', 'W', 'T', 'F', 'S', 'S'];
                    return Column(
                      children: [
                        Text(
                          days[index],
                          style: const TextStyle(
                            fontSize: 16,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        const SizedBox(height: 4),
                        Container(
                          width: 30,
                          height: 30,
                          decoration: BoxDecoration(
                            color: index.isEven ? Colors.blue : Colors.grey,
                            shape: BoxShape.circle,
                          ),
                        ),
                      ],
                    );
                  }),
                ),
              const SizedBox(height: 16),

              // Bagian Latihan Akhir
              const Text(
                "Latihan Akhir",
                style: TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 8),
              Flexible(
                child: SingleChildScrollView(
                  scrollDirection: Axis.horizontal,
                  child: Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: data.map((item) {
                      return Padding(
                        padding: const EdgeInsets.only(right: 16.0),
                        child: _buildCard(
                          title: item['nama_latihan'],
                          imageBase64: item['gambar_latihan'],
                          backgroundColor: _getCardColor(data.indexOf(item)),
                        ),
                      );
                    }).toList(),
                  ),
                ),
              ),
              const SizedBox(height: 16),

              // Bagian Latihan Hari
              const Text(
                "Latihan Hari",
                style: TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 8),
              Flexible(
                child: SingleChildScrollView(
                  scrollDirection: Axis.horizontal,
                  child: Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: data.map((item) {
                      return Padding(
                        padding: const EdgeInsets.only(right: 16.0),
                        child: _buildCard(
                          title: item['nama_latihan'],
                          imageBase64: item['gambar_latihan'],
                          backgroundColor: _getCardColor(data.indexOf(item)),
                        ),
                      );
                    }).toList(),
                  ),
                ),
              ),
            ],
          ),
        );
      }
    },
  );
}
}


// Fungsi untuk memilih warna berdasarkan indeks
Color _getCardColor(int index) {
  final colors = [
    Colors.greenAccent.shade100,
    Colors.pinkAccent.shade100,
    Colors.orangeAccent.shade100,
    Colors.purpleAccent.shade100,
  ];
  return colors[index % colors.length];
}

// Widget untuk menampilkan kartu
Widget _buildCard({
  required String title,
  required String? imageBase64,
  required Color backgroundColor,
}) {
  Uint8List? imageBytes;

  try {
    if (imageBase64 != null && imageBase64.isNotEmpty) {
      imageBytes = base64Decode(imageBase64);
    }
  } catch (e) {
    debugPrint('Error decoding Base64: $e');
    imageBytes = null;
  }

  return Column(
    crossAxisAlignment: CrossAxisAlignment.start,
       mainAxisSize: MainAxisSize.min,
    children: [
      const SizedBox(height: 1),
      Container(
        width: 200,
        height: 150,
        decoration: BoxDecoration(
          color: backgroundColor,
          borderRadius: BorderRadius.circular(16),
        ),
        child: Padding(
          padding: const EdgeInsets.all(8.0),
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              Expanded(
                child: Text(
                  title,
                  textAlign: TextAlign.left,
                  style: const TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
              const SizedBox(width: 8),
              if (imageBytes != null)
                ClipRRect(
                  borderRadius: BorderRadius.circular(12),
                  child: Image.memory(
                    imageBytes,
                    width: 80,
                    height: 80,
                    fit: BoxFit.cover,
                  ),
                )
              else
                const Icon(
                  Icons.broken_image,
                  size: 80,
                  color: Colors.grey,
                ),
            ],
          ),
        ),
      ),
    ],
  );
}
class WorkoutScreen extends StatelessWidget {
  const WorkoutScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return const Center(
      child: Text('Workout Screen'),
    );
  }
}

class ProgressScreen extends StatelessWidget {
  const ProgressScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return const Center(
      child: Text('Progress Screen'),
    );
  }
}

class AccountScreen extends StatelessWidget {
  const AccountScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Container(
          padding: const EdgeInsets.symmetric(vertical: 20),
          decoration: const BoxDecoration(
            color: Colors.greenAccent,
            borderRadius: BorderRadius.only(
              bottomLeft: Radius.circular(20),
              bottomRight: Radius.circular(20),
            ),
          ),
          child: Column(
            children: const [
              CircleAvatar(
                radius: 40,
                backgroundImage: AssetImage('assets/images/profile.png'),
              ),
              SizedBox(height: 10),
              Text(
                'Nanda',
                style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
              ),
              SizedBox(height: 10),
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Column(
                    children: [
                      Text('160 cm',
                          style: TextStyle(fontWeight: FontWeight.bold)),
                      Text('Tinggi Badan'),
                    ],
                  ),
                  SizedBox(width: 20),
                  Column(
                    children: [
                      Text('55 kg',
                          style: TextStyle(fontWeight: FontWeight.bold)),
                      Text('Berat Badan'),
                    ],
                  ),
                ],
              ),
            ],
          ),
        ),
        Expanded(
          child: ListView(
            padding: const EdgeInsets.all(16),
            children: [
              ListTile(
                leading: const Icon(Icons.settings),
                title: const Text('Settings'),
                onTap: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                        builder: (context) => const SettingsScreen()),
                  );
                },
              ),
              const ListTile(
                leading: Icon(Icons.notifications),
                title: Text('Notifications'),
              ),
              const ListTile(
                leading: Icon(Icons.help_outline),
                title: Text('Help'),
              ),
            ],
          ),
        ),
      ],
    );
  }
}

class SettingsScreen extends StatelessWidget {
  const SettingsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: const Color(0xFFB5E61D),
        title: const Text('Settings', style: TextStyle(color: Colors.black)),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back, color: Colors.black),
          onPressed: () {
            Navigator.pop(context);
          },
        ),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: const [
            SettingItem(title: 'Berat Badan', value: '55 kg'),
            SettingItem(title: 'Tinggi Badan', value: '160 cm'),
            SettingItem(title: 'Tahun Lahir', value: '1997'),
          ],
        ),
      ),
    );
  }
}

class SettingItem extends StatelessWidget {
  final String title;
  final String value;

  const SettingItem({super.key, required this.title, required this.value});

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(
              title,
              style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
            ),
            Text(
              value,
              style: const TextStyle(fontSize: 14, color: Colors.grey),
            ),
          ],
        ),
        const Divider(),
      ],
    );
  }
}
